<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use Illuminate\Http\Request;

class BancosController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Banco::query();

        if ($search) {
            $query->where('nombre', 'like', "%{$search}%")
                ->orWhere('codigo', 'like', "%{$search}%");
        }

        $bancos = $query->paginate(10)->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.bancos.partials.table-body', compact('bancos'))->render(),
                'pagination' => $bancos->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.bancos.listar', compact('bancos'));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:bancos,nombre',
            'codigo' => 'required|unique:bancos,codigo',
            'color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'logo' => 'nullable|string'
        ]);

        $banco = Banco::create([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'color' => $request->color,
            'logo' => $request->logo
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Banco registrado correctamente.',
            'banco' => $banco
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $codigo = $request->input('codigo');
        $id = $request->input('id');

        // Verificar si el nombre o el código ya existen (excepto el banco actual)
        $query = Banco::where(function ($q) use ($nombre, $codigo) {
            $q->where('nombre', $nombre)->orWhere('codigo', $codigo);
        });

        if ($id && is_numeric($id) && $id > 0) {
            $query->where('id', '!=', $id);
        }

        $existe = $query->exists();

        return response()->json(['exists' => $existe]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'codigo' => 'required|string',
            'id' => 'nullable|numeric'
        ]);

        $nombre = trim($request->input('nombre'));
        $codigo = trim($request->input('codigo'));
        $id = $request->input('id');

        $id = is_numeric($id) && $id > 0 ? (int) $id : null;

        $existe = Banco::where(function ($q) use ($nombre, $codigo) {
            $q->where('nombre', $nombre)->orWhere('codigo', $codigo);
        })
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $existe]);
    }

    public function modificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:bancos,id',
            'nombre' => 'required|unique:bancos,nombre,' . $request->id,
            'codigo' => 'required|unique:bancos,codigo,' . $request->id,
            'color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'logo' => 'nullable|string'
        ]);

        $banco = Banco::find($request->id);
        $banco->update([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'color' => $request->color,
            'logo' => $request->logo
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Banco actualizado correctamente.'
        ]);
    }

    public function eliminar(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'msg' => 'ID de banco no proporcionado'
            ], 400);
        }

        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'success' => false,
                'msg' => 'ID de banco inválido'
            ], 400);
        }

        $banco = Banco::find($id);

        if (!$banco) {
            return response()->json([
                'success' => false,
                'msg' => 'El banco no existe'
            ], 404);
        }

        // Verificar si tiene cuentas bancarias asociadas
        if ($banco->cuentas()->count() > 0) {
            return response()->json([
                'success' => false,
                'msg' => 'No se puede eliminar el banco porque tiene cuentas bancarias asociadas. Elimine primero todas las cuentas asociadas a este banco.'
            ], 400);
        }

        try {
            $banco->delete();
            return response()->json([
                'success' => true,
                'msg' => 'Banco eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar banco ID ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al intentar eliminar el banco. Por favor, inténtelo de nuevo.'
            ], 500);
        }
    }

    public function ver($id)
    {
        $banco = Banco::with('cuentas.sucursal')->findOrFail($id);

        return response()->json([
            'banco' => $banco,
            'cuentas' => $banco->cuentas
        ]);
    }

    public function detalle($id)
    {
        $banco = Banco::with([
            'cuentas' => function ($query) {
                $query->with(['sucursal', 'banco'])
                    ->orderBy('activa', 'desc')
                    ->orderBy('numero_cuenta');
            },
            'cuentas.sucursal',
            'cuentas.pagos' => function ($query) {
                $query->orderBy('fecha_pago', 'desc')
                    ->take(10); // Últimos 10 pagos
            }
        ])->findOrFail($id);

        // Estadísticas
        $estadisticas = [
            'total_cuentas' => $banco->cuentas->count(),
            'cuentas_activas' => $banco->cuentas->where('activa', true)->count(),
            'cuentas_inactivas' => $banco->cuentas->where('activa', false)->count(),
            'saldo_total' => $banco->cuentas->sum('saldo_actual'),
            'total_pagos' => $banco->cuentas->sum(function ($cuenta) {
                return $cuenta->pagos->count();
            })
        ];

        // Agrupar por moneda
        $cuentas_por_moneda = $banco->cuentas->groupBy('moneda');

        return view('admin.bancos.detalle', compact('banco', 'estadisticas', 'cuentas_por_moneda'));
    }
}
