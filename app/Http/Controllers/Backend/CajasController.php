<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\TrabajadoresCargo;
use Illuminate\Http\Request;

class CajasController extends Controller
{
    public function listarActivas()
    {
        try {
            $cajas = Caja::where('activa', true)
                ->with(['sucursal:id,nombre'])
                ->get(['id', 'nombre', 'sucursale_id', 'saldo_actual', 'moneda'])
                ->map(function ($caja) {
                    return [
                        'id' => $caja->id,
                        'nombre' => $caja->nombre,
                        'sucursal_nombre' => $caja->sucursal->nombre ?? 'Sin sucursal',
                        'saldo_actual' => $caja->saldo_actual,
                        'moneda' => $caja->moneda,
                    ];
                });

            return response()->json([
                'success' => true,
                'cajas' => $cajas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar cajas'
            ], 500);
        }
    }

    public function ver($id)
    {
        $caja = Caja::with([
            'sucursal.sede',
            'responsable.trabajador.persona',
            'responsable.cargo',
            'movimientos',
            'pagos'
        ])->findOrFail($id);

        return response()->json($caja);
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'sucursale_id' => 'required|exists:sucursales,id',
            'nombre' => 'required|string|max:255|unique:cajas,nombre,NULL,id,sucursale_id,' . $request->sucursale_id,
            'descripcion' => 'nullable|string|max:500',
            'responsable_id' => 'required|exists:trabajadores_cargos,id',
            'moneda' => 'required|in:BS,USD',
            'saldo_inicial' => 'required|numeric|min:0'
        ]);

        // Verificar que el responsable pertenezca a la sucursal
        $responsable = TrabajadoresCargo::where('id', $request->responsable_id)
            ->where('sucursale_id', $request->sucursale_id)
            ->first();

        if (!$responsable) {
            return response()->json([
                'success' => false,
                'msg' => 'El responsable no pertenece a esta sucursal.'
            ], 400);
        }

        $caja = Caja::create([
            'sucursale_id' => $request->sucursale_id,
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

    public function trabajadoresPorSucursal($sucursalId)
    {
        $trabajadores = TrabajadoresCargo::with(['trabajador.persona', 'cargo'])
            ->where('sucursale_id', $sucursalId)
            ->where('estado', 'Vigente')
            ->get()
            ->map(function ($trabajadorCargo) {
                return [
                    'id' => $trabajadorCargo->id,
                    'text' => $trabajadorCargo->trabajador->persona->nombres . ' ' .
                        $trabajadorCargo->trabajador->persona->apellido_paterno . ' - ' .
                        $trabajadorCargo->cargo->nombre
                ];
            });

        return response()->json($trabajadores);
    }
}
