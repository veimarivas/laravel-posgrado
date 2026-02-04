<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\Sucursale;
use App\Models\TrabajadoresCargo;
use Illuminate\Http\Request;

class SucursalesController extends Controller
{
    public function porSede(Request $request)
    {
        return Sucursale::where('sede_id', $request->sede_id)->get(['id', 'nombre']);
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],
            'direccion' => 'required|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'sede_id' => 'required|exists:sedes,id',
        ]);

        $sucursal = Sucursale::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'color' => $request->color,
            'sede_id' => $request->sede_id,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Sucursal registrada correctamente.',
            'sucursal' => $sucursal
        ]);
    }

    public function modificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sucursales,id',
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],
            'direccion' => 'required|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'sede_id' => 'required|exists:sedes,id',
        ]);

        $sucursal = Sucursale::findOrFail($request->id);
        $sucursal->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'color' => $request->color,
            'sede_id' => $request->sede_id,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Sucursal actualizada correctamente.',
            'sucursal' => $sucursal
        ]);
    }

    public function eliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sucursales,id'
        ]);

        $sucursal = Sucursale::findOrFail($request->id);
        $sucursal->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Sucursal eliminada correctamente.'
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'sede_id' => 'required|exists:sedes,id',
        ]);

        $query = Sucursale::where('sede_id', $request->sede_id)
            ->where('nombre', $request->nombre);

        // Excluir la sucursal actual en caso de edición
        if ($request->has('id') && $request->id) {
            $query->where('id', '!=', $request->id);
        }

        return response()->json(['exists' => $query->exists()]);
    }

    public function verDetalle($id)
    {
        $sucursal = Sucursale::with([
            'sede',
            'cuentas_bancarias.banco',
            'cajas.responsable.trabajador.persona'
        ])->findOrFail($id);

        // Obtener trabajadores y pasarlos a la vista
        $trabajadores = TrabajadoresCargo::with(['trabajador.persona', 'cargo'])
            ->where('sucursale_id', $id)
            ->where('estado', 'Vigente')
            ->whereIn('cargo_id', [4, 7])
            ->get()
            ->map(function ($tc) {
                if ($tc->trabajador && $tc->trabajador->persona) {
                    return [
                        'id' => $tc->id,
                        'text' => $tc->trabajador->persona->nombres . ' ' .
                            $tc->trabajador->persona->apellido_paterno . ' ' .
                            $tc->trabajador->persona->apellido_materno .
                            ($tc->cargo ? ' - ' . $tc->cargo->nombre : '')
                    ];
                }
                return null;
            })->filter()->values(); // Filtrar nulos y resetear índices

        // Debug: Verificar datos
        \Log::info('Trabajadores para sucursal ' . $id . ': ' . json_encode($trabajadores));

        return view('admin.sucursales.ver', compact('sucursal', 'trabajadores'));
    }

    public function trabajadoresDisponibles($id)
    {
        // Obtener los trabajadores_cargos que pertenecen a esta sucursal
        $trabajadoresCargos = TrabajadoresCargo::with([
            'trabajador.persona',  // Relación a Trabajadores -> Persona
            'cargo'                // Relación a Cargo
        ])
            ->where('sucursale_id', $id)
            ->where('estado', 'Vigente')
            ->get();

        // Formatear para Select2
        $formatted = $trabajadoresCargos->map(function ($trabajadorCargo) {
            // Verificar que existan las relaciones
            if (!$trabajadorCargo->trabajador || !$trabajadorCargo->trabajador->persona) {
                return null;
            }

            $persona = $trabajadorCargo->trabajador->persona;
            $cargo = $trabajadorCargo->cargo;

            return [
                'id' => $trabajadorCargo->id,
                'text' => $persona->nombres . ' ' .
                    $persona->apellido_paterno . ' ' .
                    $persona->apellido_materno .
                    ($cargo ? ' - ' . $cargo->nombre : '')
            ];
        })->filter(); // Filtrar nulos

        return response()->json($formatted->values());
    }

    public function registrarCaja(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'responsable_id' => 'required|exists:trabajadores_cargos,id',
            'moneda' => 'required|in:BS,USD',
            'saldo_inicial' => 'required|numeric|min:0'
        ]);

        // Verificar que el responsable pertenezca a esta sucursal
        $responsable = TrabajadoresCargo::where('id', $request->responsable_id)
            ->where('sucursale_id', $id)
            ->first();

        if (!$responsable) {
            return response()->json([
                'success' => false,
                'msg' => 'El responsable seleccionado no pertenece a esta sucursal.'
            ], 400);
        }

        $caja = Caja::create([
            'sucursale_id' => $id,
            'responsable_id' => $request->responsable_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'moneda' => $request->moneda,
            'saldo_inicial' => $request->saldo_inicial,
            'saldo_actual' => $request->saldo_inicial,
            'activa' => true
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Caja registrada correctamente.',
            'caja' => $caja
        ]);
    }
}
