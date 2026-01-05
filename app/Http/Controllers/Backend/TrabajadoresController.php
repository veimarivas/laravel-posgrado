<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Models\Ciudade;
use App\Models\Departamento;
use App\Models\Persona;
use App\Models\Sucursale;
use App\Models\Trabajadore;
use App\Models\TrabajadoresCargo;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class TrabajadoresController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');
        $query = Trabajadore::with([
            'persona',
            'trabajadores_cargos.cargo',
            'trabajadores_cargos.sucursal.sede' // Cargar sede a través de sucursal
        ]);

        if ($search) {
            $query->whereHas('persona', function ($q) use ($search) {
                $q->where('carnet', 'like', "%{$search}%")
                    ->orWhere('nombres', 'like', "%{$search}%")
                    ->orWhere('apellido_paterno', 'like', "%{$search}%")
                    ->orWhere('apellido_materno', 'like', "%{$search}%");
            });
        }

        $trabajadores = $query->paginate(10)->appends(['search' => $search]);

        // Cargar catálogos para el formulario
        $departamentos = Departamento::all();
        $ciudades = Ciudade::with('departamento')->get();
        $cargos = Cargo::all();
        $sucursales = Sucursale::with('sede')->get();

        return view('admin.trabajadores.listar', compact(
            'trabajadores',
            'departamentos',
            'ciudades',
            'cargos',
            'sucursales'
        ));
    }

    public function listarVendedores(Request $request)
    {
        try {
            $search = $request->get('search', '');

            // Filtrar trabajadores con cargos específicos (2, 3, 6)
            $query = Trabajadore::whereHas('trabajadores_cargos', function ($q) {
                $q->whereIn('cargo_id', [2, 3, 6])
                    ->where('estado', 'Vigente');
            })
                ->with([
                    'persona.ciudad.departamento',
                    'persona' => function ($query) use ($search) {
                        if ($search) {
                            $query->where(function ($q) use ($search) {
                                $q->where('nombres', 'like', "%{$search}%")
                                    ->orWhere('carnet', 'like', "%{$search}%")
                                    ->orWhere('apellido_paterno', 'like', "%{$search}%")
                                    ->orWhere('apellido_materno', 'like', "%{$search}%")
                                    ->orWhere('correo', 'like', "%{$search}%")
                                    ->orWhere('celular', 'like', "%{$search}%");
                            });
                        }
                    },
                    'trabajadores_cargos' => function ($q) {
                        $q->whereIn('cargo_id', [2, 3, 6])
                            ->where('estado', 'Vigente')
                            ->with(['cargo', 'sucursal']);
                    }
                ]);

            // Aplicar búsqueda adicional en la consulta principal
            if ($search) {
                $query->whereHas('persona', function ($q) use ($search) {
                    $q->where('nombres', 'like', "%{$search}%")
                        ->orWhere('carnet', 'like', "%{$search}%")
                        ->orWhere('apellido_paterno', 'like', "%{$search}%")
                        ->orWhere('apellido_materno', 'like', "%{$search}%")
                        ->orWhere('correo', 'like', "%{$search}%")
                        ->orWhere('celular', 'like', "%{$search}%");
                });
            }

            $vendedores = $query->paginate(10);

            if ($request->ajax()) {
                $view = view('admin.trabajadores.vendedores.partials.table-body', compact('vendedores'))->render();
                $pagination = $vendedores->links('pagination::bootstrap-5')->toHtml();

                return response()->json([
                    'html' => $view,
                    'pagination' => $pagination,
                    'total' => $vendedores->total(),
                    'from' => $vendedores->firstItem(),
                    'to' => $vendedores->lastItem()
                ]);
            }

            return view('admin.trabajadores.vendedores.listar', compact('vendedores'));
        } catch (\Exception $e) {
            \Log::error('Error en listarVendedores: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Error al cargar los resultados',
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error al cargar los vendedores');
        }
    }

    public function verificarCarnet(Request $request)
    {
        $carnet = $request->carnet;
        $persona = Persona::where('carnet', $carnet)->first();

        if (!$persona) {
            return response()->json([
                'exists' => false,
                'is_worker' => false,
                'message' => 'La persona no está registrada.'
            ]);
        }

        $trabajador = Trabajadore::where('persona_id', $persona->id)->first();

        if ($trabajador) {
            return response()->json([
                'exists' => true,
                'is_worker' => true,
                'message' => 'Ya es trabajador la persona.',
                'persona' => [
                    'id' => $persona->id,
                    'nombre_completo' => trim("{$persona->apellido_paterno} {$persona->apellido_materno}, {$persona->nombres}"),
                    'carnet' => $persona->carnet,
                ],
                'trabajador_id' => $trabajador->id
            ]);
        }

        return response()->json([
            'exists' => true,
            'is_worker' => false,
            'message' => 'La persona está registrada pero no es trabajador.',
            'persona' => [
                'id' => $persona->id,
                'nombre_completo' => trim("{$persona->apellido_paterno} {$persona->apellido_materno}, {$persona->nombres}"),
                'carnet' => $persona->carnet,
            ]
        ]);
    }

    public function registrar(Request $request)
    {
        try {
            $validated = $request->validate([
                'persona_id' => 'required|exists:personas,id',
                'cargo_id' => 'required|exists:cargos,id',
                'sucursal_id' => 'nullable|exists:sucursales,id', // Cambiado a nullable
                'fecha_ingreso' => 'required|date',
            ]);

            // Verificar que no esté ya registrado como trabajador
            if (Trabajadore::where('persona_id', $request->persona_id)->exists()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Esta persona ya está registrada como trabajador.'
                ], 422);
            }

            // Crear trabajador
            $trabajador = Trabajadore::create([
                'persona_id' => $request->persona_id,
            ]);

            // Obtener nombre del cargo
            $cargo = Cargo::find($request->cargo_id);
            if (!$cargo) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Cargo no encontrado.'
                ], 422);
            }

            $cargoNombre = $cargo->nombre;

            // Definir cargos gerenciales (sin sucursal específica)
            $cargosGerenciales = ['Director Académico', 'Gerente de Marketing', 'Director Financiera Contable'];

            // Lógica específica para cada tipo de cargo
            if (in_array($cargoNombre, $cargosGerenciales)) {
                // Para cargos gerenciales, no asignar sucursal específica
                TrabajadoresCargo::create([
                    'trabajadore_id' => $trabajador->id,
                    'cargo_id' => $request->cargo_id,
                    'sucursale_id' => null, // NULL para gerentes
                    'principal' => 1,
                    'estado' => 'Vigente',
                    'fecha_ingreso' => $request->fecha_ingreso,
                ]);
            } elseif ($cargoNombre === 'Encargado Académico' || $cargoNombre === 'Ejecutivo Contable') {
                // Solo registrar un cargo (el principal) en la sucursal seleccionada
                TrabajadoresCargo::create([
                    'trabajadore_id' => $trabajador->id,
                    'cargo_id' => $request->cargo_id,
                    'sucursale_id' => $request->sucursal_id,
                    'principal' => 1,
                    'estado' => 'Vigente',
                    'fecha_ingreso' => $request->fecha_ingreso,
                ]);
            } elseif ($cargoNombre === 'Ejecutivo de Marketing') {
                // Registrar como Ejecutivo de Marketing en la sucursal seleccionada (principal)
                TrabajadoresCargo::create([
                    'trabajadore_id' => $trabajador->id,
                    'cargo_id' => $request->cargo_id,
                    'sucursale_id' => $request->sucursal_id,
                    'principal' => 1,
                    'estado' => 'Vigente',
                    'fecha_ingreso' => $request->fecha_ingreso,
                ]);

                // Encontrar ID del cargo "Asesor de Marketing"
                $asesorMarketing = Cargo::where('nombre', 'Asesor de Marketing')->first();
                if (!$asesorMarketing) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'El cargo "Asesor de Marketing" no existe en la base de datos.'
                    ], 422);
                }

                // Registrar como Asesor de Marketing en todas las demás sucursales
                $sucursales = Sucursale::where('id', '!=', $request->sucursal_id)->get();
                foreach ($sucursales as $sucursal) {
                    TrabajadoresCargo::create([
                        'trabajadore_id' => $trabajador->id,
                        'cargo_id' => $asesorMarketing->id,
                        'sucursale_id' => $sucursal->id,
                        'principal' => 0,
                        'estado' => 'Vigente',
                        'fecha_ingreso' => $request->fecha_ingreso,
                    ]);
                }
            } else { // Asesor de Marketing
                // Registrar como Asesor de Marketing en la sucursal seleccionada (principal)
                TrabajadoresCargo::create([
                    'trabajadore_id' => $trabajador->id,
                    'cargo_id' => $request->cargo_id,
                    'sucursale_id' => $request->sucursal_id,
                    'principal' => 1,
                    'estado' => 'Vigente',
                    'fecha_ingreso' => $request->fecha_ingreso,
                ]);

                // Encontrar ID del cargo "Asesor de Marketing" (para seguridad)
                $asesorMarketing = Cargo::where('nombre', 'Asesor de Marketing')->first();
                if (!$asesorMarketing) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'El cargo "Asesor de Marketing" no existe en la base de datos.'
                    ], 422);
                }

                // Registrar como Asesor de Marketing en todas las demás sucursales
                $sucursales = Sucursale::where('id', '!=', $request->sucursal_id)->get();
                foreach ($sucursales as $sucursal) {
                    TrabajadoresCargo::create([
                        'trabajadore_id' => $trabajador->id,
                        'cargo_id' => $asesorMarketing->id,
                        'sucursale_id' => $sucursal->id,
                        'principal' => 0,
                        'estado' => 'Vigente',
                        'fecha_ingreso' => $request->fecha_ingreso,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'msg' => 'Trabajador registrado correctamente con sus cargos.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al registrar trabajador: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function eliminar(Request $request)
    {
        $request->validate(['id' => 'required|exists:trabajadores,id']);

        // Primero eliminar los cargos asociados
        TrabajadoresCargo::where('trabajadore_id', $request->id)->delete();

        // Luego eliminar el trabajador
        Trabajadore::where('id', $request->id)->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Trabajador eliminado correctamente.'
        ]);
    }

    public function registrarPersonaYTrabajador(Request $request)
    {
        $request->validate([
            'carnet' => 'required|unique:personas,carnet',
            'nombres' => 'required|string|max:255',
            'expedido' => 'nullable|in:Lp,Or,Pt,Cb,Ch,Tj,Be,Sc,Pn',
            'apellido_paterno' => 'nullable|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'sexo' => 'required|in:Hombre,Mujer',
            'estado_civil' => 'required|in:Soltero(a),Casado(a),Divorciado(a),Viudo(a)',
            'fecha_nacimiento' => 'nullable|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'correo' => 'required|email|unique:personas,correo',
            'celular' => 'required|numeric',
            'telefono' => 'nullable|numeric',
            'ciudade_id' => 'required|exists:ciudades,id',
            'cargo_id' => 'required|exists:cargos,id',
            'sucursal_id' => 'nullable|exists:sucursales,id', // Cambiado a nullable
            'fecha_ingreso' => 'required|date',
        ]);

        if (empty($request->apellido_paterno) && empty($request->apellido_materno)) {
            return response()->json([
                'success' => false,
                'errors' => ['apellidos' => ['Debe ingresar al menos un apellido.']]
            ], 422);
        }

        // Crear persona
        $persona = Persona::create($request->only([
            'carnet',
            'nombres',
            'expedido',
            'apellido_paterno',
            'apellido_materno',
            'sexo',
            'estado_civil',
            'fecha_nacimiento',
            'correo',
            'direccion',
            'celular',
            'telefono',
            'ciudade_id'
        ]));

        // Crear trabajador
        $trabajador = Trabajadore::create(['persona_id' => $persona->id]);

        // Obtener nombre del cargo
        $cargoNombre = Cargo::find($request->cargo_id)->nombre;

        // Definir cargos gerenciales (sin sucursal específica)
        $cargosGerenciales = ['Director Académico', 'Gerente de Marketing', 'Director Financiera Contable'];

        // Lógica específica para cada tipo de cargo
        if (in_array($cargoNombre, $cargosGerenciales)) {
            // Para cargos gerenciales, no asignar sucursal específica
            TrabajadoresCargo::create([
                'trabajadore_id' => $trabajador->id,
                'cargo_id' => $request->cargo_id,
                'sucursale_id' => null, // NULL para gerentes
                'principal' => 1,
                'estado' => 'Vigente',
                'fecha_ingreso' => $request->fecha_ingreso,
            ]);
        } elseif ($cargoNombre === 'Encargado Académico' || $cargoNombre === 'Ejecutivo Contable') {
            // Solo registrar un cargo (el principal) en la sucursal seleccionada
            TrabajadoresCargo::create([
                'trabajadore_id' => $trabajador->id,
                'cargo_id' => $request->cargo_id,
                'sucursale_id' => $request->sucursal_id,
                'principal' => 1,
                'estado' => 'Vigente',
                'fecha_ingreso' => $request->fecha_ingreso,
            ]);
        } elseif ($cargoNombre === 'Ejecutivo de Marketing') {
            // Registrar como Ejecutivo de Marketing en la sucursal seleccionada (principal)
            TrabajadoresCargo::create([
                'trabajadore_id' => $trabajador->id,
                'cargo_id' => $request->cargo_id,
                'sucursale_id' => $request->sucursal_id,
                'principal' => 1,
                'estado' => 'Vigente',
                'fecha_ingreso' => $request->fecha_ingreso,
            ]);

            // Encontrar ID del cargo "Asesor de Marketing"
            $asesorMarketingId = Cargo::where('nombre', 'Asesor de Marketing')->value('id');

            // Si el cargo existe, registrar como Asesor de Marketing en todas las demás sucursales
            if ($asesorMarketingId) {
                $sucursales = Sucursale::where('id', '!=', $request->sucursal_id)->get();
                foreach ($sucursales as $sucursal) {
                    TrabajadoresCargo::create([
                        'trabajadore_id' => $trabajador->id,
                        'cargo_id' => $asesorMarketingId,
                        'sucursale_id' => $sucursal->id,
                        'principal' => 0,
                        'estado' => 'Vigente',
                        'fecha_ingreso' => $request->fecha_ingreso,
                    ]);
                }
            }
        } else { // Asesor de Marketing
            // Registrar como Asesor de Marketing en la sucursal seleccionada (principal)
            TrabajadoresCargo::create([
                'trabajadore_id' => $trabajador->id,
                'cargo_id' => $request->cargo_id,
                'sucursale_id' => $request->sucursal_id,
                'principal' => 1,
                'estado' => 'Vigente',
                'fecha_ingreso' => $request->fecha_ingreso,
            ]);

            // Encontrar ID del cargo "Asesor de Marketing" (para seguridad)
            $asesorMarketingId = Cargo::where('nombre', 'Asesor de Marketing')->value('id');

            // Si el cargo existe, registrar como Asesor de Marketing en todas las demás sucursales
            if ($asesorMarketingId) {
                $sucursales = Sucursale::where('id', '!=', $request->sucursal_id)->get();
                foreach ($sucursales as $sucursal) {
                    TrabajadoresCargo::create([
                        'trabajadore_id' => $trabajador->id,
                        'cargo_id' => $asesorMarketingId,
                        'sucursale_id' => $sucursal->id,
                        'principal' => 0,
                        'estado' => 'Vigente',
                        'fecha_ingreso' => $request->fecha_ingreso,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'msg' => 'Persona registrada y asignada como trabajador correctamente.',
            'worker_id' => $trabajador->id
        ]);
    }

    /**
     * Actualizar el estado de un cargo de un trabajador
     */
    public function actualizarEstadoCargo(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:trabajadores_cargos,id',
            'estado' => 'required|in:Vigente,No Vigente'
        ]);

        $cargoTrabajador = TrabajadoresCargo::find($request->id);

        // Si se está cambiando a "No Vigente", establecer fecha de término
        if ($request->estado === 'No Vigente' && $cargoTrabajador->estado === 'Vigente') {
            $cargoTrabajador->fecha_termino = now();
        }
        // Si se está cambiando a "Vigente", quitar fecha de término
        elseif ($request->estado === 'Vigente' && $cargoTrabajador->estado === 'No Vigente') {
            $cargoTrabajador->fecha_termino = null;
        }

        $cargoTrabajador->estado = $request->estado;
        $cargoTrabajador->save();

        return response()->json([
            'success' => true,
            'msg' => 'Estado del cargo actualizado correctamente.'
        ]);
    }

    /**
     * Verificar si un trabajador ya tiene un cargo específico en una sucursal
     */
    public function verificarCargoExistente(Request $request)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id',
            'cargo_id' => 'required|exists:cargos,id',
            'sucursal_id' => 'required|exists:sucursales,id'
        ]);

        $trabajadorId = $request->trabajador_id;
        $cargoId = $request->cargo_id;
        $sucursalId = $request->sucursal_id;

        $tieneEnMismaSucursal = TrabajadoresCargo::where('trabajadore_id', $trabajadorId)
            ->where('cargo_id', $cargoId)
            ->where('sucursale_id', $sucursalId)
            ->exists();

        return response()->json([
            'tiene_en_misma_sucursal' => $tieneEnMismaSucursal
        ]);
    }

    /**
     * Obtener cargos disponibles para un trabajador (todos los cargos)
     */
    public function cargosDisponibles(Request $request)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id',
        ]);

        // Obtener TODOS los cargos, no filtrar por los que ya tiene
        $cargosDisponibles = Cargo::all();

        return response()->json([
            'cargos' => $cargosDisponibles
        ]);
    }

    /**
     * Obtener sucursales disponibles para un trabajador según el tipo de cargo
     */
    public function sucursalesDisponibles(Request $request)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id',
            'cargo_id' => 'required|exists:cargos,id'
        ]);

        $trabajadorId = $request->trabajador_id;
        $cargoId = $request->cargo_id;

        \Log::info("Buscando sucursales para trabajador: {$trabajadorId}, cargo: {$cargoId}");

        // Para TODOS los cargos: excluir solo las sucursales donde ya tiene ESTE cargo
        $sucursalesAsignadas = TrabajadoresCargo::where('trabajadore_id', $trabajadorId)
            ->where('cargo_id', $cargoId)
            ->pluck('sucursale_id')
            ->toArray();

        \Log::info("Sucursales asignadas: " . json_encode($sucursalesAsignadas));

        $sucursalesDisponibles = Sucursale::with('sede')
            ->whereNotIn('id', $sucursalesAsignadas)
            ->get();

        \Log::info("Sucursales disponibles: " . $sucursalesDisponibles->count());

        return response()->json(['sucursales' => $sucursalesDisponibles]);
    }

    /**
     * Asignar un nuevo cargo a un trabajador existente
     */
    public function asignarNuevoCargo(Request $request)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id',
            'cargo_id' => 'required|exists:cargos,id',
            'sucursal_id' => 'nullable|exists:sucursales,id',
            'fecha_ingreso' => 'required|date'
        ]);

        $cargo = Cargo::find($request->cargo_id);
        $cargoNombre = $cargo->nombre;
        $cargosGerenciales = ['Director Académico', 'Gerente de Marketing', 'Director Financiera Contable'];

        // Para cargos gerenciales, verificar que no tenga el mismo cargo
        if (in_array($cargoNombre, $cargosGerenciales)) {
            $yaTieneCargo = TrabajadoresCargo::where('trabajadore_id', $request->trabajador_id)
                ->where('cargo_id', $request->cargo_id)
                ->exists();

            if ($yaTieneCargo) {
                return response()->json([
                    'success' => false,
                    'msg' => "El trabajador ya tiene asignado el cargo gerencial {$cargoNombre}."
                ], 422);
            }
        } else {
            // Para cargos no gerenciales, verificar sucursal
            if (!$request->sucursal_id) {
                return response()->json([
                    'success' => false,
                    'msg' => "Debe seleccionar una sucursal para este cargo."
                ], 422);
            }

            $yaTieneEnSucursal = TrabajadoresCargo::where('trabajadore_id', $request->trabajador_id)
                ->where('cargo_id', $request->cargo_id)
                ->where('sucursale_id', $request->sucursal_id)
                ->exists();

            if ($yaTieneEnSucursal) {
                return response()->json([
                    'success' => false,
                    'msg' => "El trabajador ya tiene este cargo asignado en la sucursal seleccionada."
                ], 422);
            }
        }

        // Crear el nuevo cargo
        TrabajadoresCargo::create([
            'trabajadore_id' => $request->trabajador_id,
            'cargo_id' => $request->cargo_id,
            'sucursale_id' => in_array($cargoNombre, $cargosGerenciales) ? null : $request->sucursal_id,
            'principal' => 1,
            'estado' => 'Vigente',
            'fecha_ingreso' => $request->fecha_ingreso,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Nuevo cargo asignado correctamente.'
        ]);
    }



    /**
     * Subir o cambiar fotografía de persona
     */
    public function subirFoto(Request $request)
    {
        try {
            $request->validate([
                'persona_id' => 'required|exists:personas,id',
                'fotografia' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $persona = Persona::find($request->persona_id);

            if (!$persona) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Persona no encontrada.'
                ], 404);
            }

            // Eliminar foto anterior si existe
            if ($persona->fotografia && file_exists(public_path($persona->fotografia))) {
                unlink(public_path($persona->fotografia));
            }

            // Procesar la imagen
            $image = $request->file('fotografia');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Crear directorio si no existe
            $path = public_path('upload/personas');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            // Redimensionar y guardar imagen
            Image::make($image)
                ->fit(500, 500)
                ->save($path . '/' . $name_gen);

            // Guardar ruta en la base de datos
            $persona->fotografia = 'upload/personas/' . $name_gen;
            $persona->save();

            return response()->json([
                'success' => true,
                'msg' => 'Fotografía actualizada correctamente.',
                'foto_url' => asset('upload/personas/' . $name_gen)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al subir foto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al subir la fotografía: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar el estado principal de un cargo de un trabajador
     */
    public function actualizarPrincipalCargo(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:trabajadores_cargos,id',
            'principal' => 'required|in:0,1',
            'cargo_id' => 'required|exists:cargos,id',
            'trabajador_id' => 'required|exists:trabajadores,id'
        ]);

        try {
            // Si se está marcando como principal (1), primero desmarcar todos los demás del mismo cargo
            if ($request->principal == 1) {
                TrabajadoresCargo::where('trabajadore_id', $request->trabajador_id)
                    ->where('cargo_id', $request->cargo_id)
                    ->update(['principal' => 0]);
            }

            // Actualizar el cargo específico
            $cargoTrabajador = TrabajadoresCargo::find($request->id);
            $cargoTrabajador->principal = $request->principal;
            $cargoTrabajador->save();

            return response()->json([
                'success' => true,
                'msg' => $request->principal == 1
                    ? 'Cargo marcado como principal correctamente.'
                    : 'Cargo quitado como principal correctamente.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar cargo principal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al actualizar el estado principal del cargo.'
            ], 500);
        }
    }

    /**
     * Verificar si un trabajador ya tiene usuario asociado
     */
    public function verificarUsuarioExistente(Request $request)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id'
        ]);

        $trabajador = Trabajadore::with('persona.usuario')->findOrFail($request->trabajador_id);
        $tieneUsuario = $trabajador->persona->usuario ? true : false;

        if ($tieneUsuario) {
            return response()->json([
                'exists' => true,
                'msg' => 'Este trabajador ya tiene una cuenta de usuario asociada.',
                'usuario' => [
                    'email' => $trabajador->persona->usuario->email,
                    'role' => $trabajador->persona->usuario->getRoleNames()->first(),
                    'estado' => $trabajador->persona->usuario->estado
                ]
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * Registrar usuario para un trabajador existente
     */
    public function registrarUsuarioTrabajador(Request $request)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'estado' => 'required|in:Activo,Inactivo'
        ]);

        // Verificar si ya tiene usuario
        $trabajador = Trabajadore::with('persona.usuario')->findOrFail($request->trabajador_id);
        if ($trabajador->persona->usuario) {
            return response()->json([
                'success' => false,
                'msg' => 'Este trabajador ya tiene una cuenta de usuario asociada.'
            ], 422);
        }

        // Verificar si el correo ya está en uso por otra persona
        $personaConEmail = Persona::whereHas('usuario', function ($query) use ($request) {
            $query->where('email', $request->email);
        })->first();

        if ($personaConEmail) {
            return response()->json([
                'success' => false,
                'msg' => 'El correo electrónico ya está registrado para otro usuario.'
            ], 422);
        }

        // Crear usuario
        $usuario = User::create([
            'name' => trim("{$trabajador->persona->nombres} {$trabajador->persona->apellido_paterno}"),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'estado' => $request->estado,
            'persona_id' => $trabajador->persona->id
        ]);

        // Asignar rol
        $usuario->assignRole($request->role);

        // Actualizar nombre en base al rol
        $nombreRol = $request->role;
        if ($nombreRol === 'super-admin') {
            $usuario->name = 'Super Administrador';
        } elseif ($nombreRol === 'admin') {
            $usuario->name = 'Administrador';
        } else {
            $usuario->name = trim("{$trabajador->persona->nombres} {$trabajador->persona->apellido_paterno}");
        }
        $usuario->save();

        return response()->json([
            'success' => true,
            'msg' => 'Usuario creado correctamente para el trabajador.',
            'usuario' => [
                'email' => $usuario->email,
                'role' => $usuario->getRoleNames()->first(),
                'estado' => $usuario->estado
            ]
        ]);
    }

    /**
     * Obtener roles disponibles para asignar a usuarios
     */
    public function obtenerRolesDisponibles()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        return response()->json(['roles' => $roles]);
    }
}
