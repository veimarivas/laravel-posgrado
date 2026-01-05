<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Ciudade;
use App\Models\Departamento;
use App\Models\Estudio;
use App\Models\GradosAcademico;
use App\Models\Persona;
use App\Models\Profesione;
use App\Models\Universidade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PersonasController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');
        $query = Persona::with('ciudad', 'estudios.grado_academico', 'estudios.profesion', 'estudios.universidad');

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

        $personas = $query->paginate(10);
        $personas->appends(['search' => $search]);

        // Cargar datos para formularios
        $departamentos = Departamento::all();
        $ciudades = Ciudade::with('departamento')->get();
        $grados = GradosAcademico::all();
        $profesiones = Profesione::all();
        $universidades = Universidade::all();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.personas.partials.table-body', compact('personas'))->render(),
                'pagination' => $personas->links('pagination::bootstrap-5')->toHtml(),
                'total' => $personas->total(),
                'from' => $personas->firstItem(),
                'to' => $personas->lastItem()
            ]);
        }

        return view('admin.personas.listar', compact('personas', 'departamentos', 'ciudades', 'grados', 'profesiones', 'universidades'));
    }

    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            'ciudade_id' => 'nullable|exists:ciudades,id',
        ]);

        // Validación personalizada: al menos un apellido
        if (empty($request->apellido_paterno) && empty($request->apellido_materno)) {
            $validator->errors()->add('apellidos', 'Debe ingresar al menos un apellido.');
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

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

        // Guardar estudios si existen
        if ($request->has('estudios') && is_array($request->estudios)) {
            foreach ($request->estudios as $estudio) {
                if (!empty($estudio['grado']) && !empty($estudio['profesion']) && !empty($estudio['universidad'])) {
                    $persona->estudios()->create([
                        'grados_academico_id' => $estudio['grado'],
                        'profesione_id' => $estudio['profesion'],
                        'universidade_id' => $estudio['universidad'],
                        'estado' => $estudio['estado'] ?? 'Concluido',
                        'documento' => $estudio['documento'] ?? null,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'msg' => 'Persona registrada correctamente.',
            'persona' => $persona
        ]);
    }

    public function verificarCarnet(Request $request)
    {
        $exists = Persona::where('carnet', $request->carnet)
            ->when($request->id, fn($q) => $q->where('id', '!=', $request->id))
            ->exists();
        return response()->json(['exists' => $exists]);
    }

    public function verificarCorreo(Request $request)
    {
        $exists = Persona::where('correo', $request->correo)
            ->when($request->id, fn($q) => $q->where('id', '!=', $request->id))
            ->exists();
        return response()->json(['exists' => $exists]);
    }

    public function verificarEdicion(Request $request)
    {
        $persona = Persona::find($request->id);

        if (!$persona) {
            return response()->json([
                'carnet_exists' => false,
                'correo_exists' => false,
            ]);
        }

        return response()->json([
            'carnet_exists' => Persona::where('carnet', $request->carnet)
                ->where('id', '!=', $persona->id)->exists(),
            'correo_exists' => Persona::where('correo', $request->correo)
                ->where('id', '!=', $persona->id)->exists(),
        ]);
    }

    public function modificar(Request $request)
    {
        // Primero, encontrar la persona para poder usar su ID en las reglas unique
        $persona = Persona::findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:personas,id',
            'carnet' => 'required|unique:personas,carnet,' . $persona->id,
            'nombres' => 'required|string|max:255',
            'expedido' => 'nullable|in:Lp,Or,Pt,Cb,Ch,Tj,Be,Sc,Pn',
            'apellido_paterno' => 'nullable|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'sexo' => 'required|in:Hombre,Mujer',
            'estado_civil' => 'required|in:Soltero(a),Casado(a),Divorciado(a),Viudo(a)',
            'fecha_nacimiento' => 'nullable|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'correo' => 'required|email|unique:personas,correo,' . $persona->id,
            'celular' => 'required|numeric',
            'telefono' => 'nullable|numeric',
            'ciudade_id' => 'nullable|exists:ciudades,id',
            'direccion' => 'nullable|string|max:500',
        ]);

        // Validación personalizada: al menos un apellido
        if (empty($request->apellido_paterno) && empty($request->apellido_materno)) {
            $validator->errors()->add('apellidos', 'Debe ingresar al menos un apellido.');
        }

        // Validación personalizada: verificar carnet único si cambió
        if ($request->carnet !== $persona->carnet) {
            $exists = Persona::where('carnet', $request->carnet)
                ->where('id', '!=', $persona->id)
                ->exists();
            if ($exists) {
                $validator->errors()->add('carnet', 'El carnet ya está registrado por otra persona.');
            }
        }

        // Validación personalizada: verificar correo único si cambió
        if ($request->correo !== $persona->correo) {
            $exists = Persona::where('correo', $request->correo)
                ->where('id', '!=', $persona->id)
                ->exists();
            if ($exists) {
                $validator->errors()->add('correo', 'El correo ya está registrado por otra persona.');
            }
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Actualizar persona
        $persona->update($request->only([
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

        // Actualizar estudios (si es que existen)
        if ($request->has('estudios_edit') && is_array($request->estudios_edit)) {
            $idsNuevos = [];

            foreach ($request->estudios_edit as $item) {
                // Verificar que todos los campos necesarios estén presentes
                if (!empty($item['grado']) && !empty($item['profesion']) && !empty($item['universidad'])) {
                    if (!empty($item['id'])) {
                        // Verificar que el estudio pertenezca a esta persona
                        $estudio = Estudio::where('id', $item['id'])
                            ->where('persona_id', $persona->id)
                            ->first();

                        if ($estudio) {
                            // Actualizar existente
                            $estudio->update([
                                'grados_academico_id' => $item['grado'],
                                'profesione_id' => $item['profesion'],
                                'universidade_id' => $item['universidad'],
                                'estado' => $item['estado'] ?? 'Concluido',
                            ]);
                            $idsNuevos[] = $item['id'];
                        }
                    } else {
                        // Crear nuevo
                        $nuevo = $persona->estudios()->create([
                            'grados_academico_id' => $item['grado'],
                            'profesione_id' => $item['profesion'],
                            'universidade_id' => $item['universidad'],
                            'estado' => $item['estado'] ?? 'Concluido',
                        ]);
                        $idsNuevos[] = $nuevo->id;
                    }
                }
            }

            // Eliminar los estudios que no están en la lista (solo si hay IDs nuevos)
            if (!empty($idsNuevos)) {
                $persona->estudios()->whereNotIn('id', $idsNuevos)->delete();
            }
        }

        return response()->json([
            'success' => true,
            'msg' => 'Persona actualizada correctamente.'
        ]);
    }

    public function eliminar(Request $request)
    {
        $request->validate(['id' => 'required|exists:personas,id']);

        $persona = Persona::findOrFail($request->id);
        $persona->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Persona eliminada correctamente.'
        ]);
    }

    public function ver($id)
    {
        // CAMBIO IMPORTANTE: Usar los nombres correctos de las relaciones
        $persona = Persona::with(['ciudad.departamento', 'estudios.grado_academico', 'estudios.profesion', 'estudios.universidad'])
            ->findOrFail($id);

        // Formatear fecha para input date
        if ($persona->fecha_nacimiento) {
            $persona->fecha_nacimiento_formatted = \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('Y-m-d');
        }

        // Preparar estudios para el formulario
        $persona->estudios = $persona->estudios->map(function ($estudio) {
            return [
                'id' => $estudio->id,
                'grado' => $estudio->grados_academico_id,
                'profesion' => $estudio->profesione_id,
                'universidad' => $estudio->universidade_id,
                'grado_nombre' => $estudio->grado_academico->nombre ?? '',
                'profesion_nombre' => $estudio->profesion->nombre ?? '',
                'universidad_nombre' => $estudio->universidad->nombre ?? '',
            ];
        });

        return response()->json([
            'success' => true,
            'persona' => $persona
        ]);
    }
}
