<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PlanesPago;
use Illuminate\Http\Request;

class PlanesPagosController extends Controller
{
    public function planesListar(Request $request)
    {
        $search = $request->get('search', '');
        $filter = $request->get('filter', 'all');
        $stats = $request->get('stats', false);

        $query = PlanesPago::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        // Aplicar filtros
        if ($filter === 'habilitado') {
            $query->where('habilitado', 1);
        } elseif ($filter === 'principal') {
            $query->where('principal', 1);
        } elseif ($filter === 'promocion') {
            $query->where('es_promocion', 1);
        } elseif ($filter === 'vigente') {
            $query->where('es_promocion', 1)
                ->where('fecha_inicio_promocion', '<=', now())
                ->where('fecha_fin_promocion', '>=', now());
        }

        $planes = $query->paginate(10);
        $planes->appends(['search' => $search, 'filter' => $filter]);

        // Formatear las fechas para JSON
        $planes->transform(function ($plan) {
            // Formatear fechas para el JSON
            if ($plan->fecha_inicio_promocion) {
                $plan->fecha_inicio_promocion_formatted = $plan->fecha_inicio_promocion->format('Y-m-d');
            }
            if ($plan->fecha_fin_promocion) {
                $plan->fecha_fin_promocion_formatted = $plan->fecha_fin_promocion->format('Y-m-d');
            }
            return $plan;
        });

        if ($stats) {
            return response()->json([
                'stats' => [
                    'habilitados' => PlanesPago::where('habilitado', 1)->count(),
                    'principales' => PlanesPago::where('principal', 1)->count(),
                    'promociones' => PlanesPago::where('es_promocion', 1)->count(),
                ]
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.planes.partials.table-body', compact('planes'))->render(),
                'pagination' => $planes->links('pagination::bootstrap-5')->toHtml(),
                'total' => $planes->total(),
                'from' => $planes->firstItem(),
                'to' => $planes->lastItem(),
                'habilitados' => PlanesPago::where('habilitado', 1)->count(),
                'principales' => PlanesPago::where('principal', 1)->count(),
                'promociones' => PlanesPago::where('es_promocion', 1)->count(),
            ]);
        }

        return view('admin.planes.listar', compact('planes'));
    }

    public function planesRegistrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:planes_pagos,nombre',
            'habilitado' => 'nullable|boolean',
            'principal' => 'nullable|boolean',
            'es_promocion' => 'nullable|boolean',
            'fecha_inicio_promocion' => 'nullable|date|required_if:es_promocion,1',
            'fecha_fin_promocion' => 'nullable|date|after:fecha_inicio_promocion|required_if:es_promocion,1',
        ]);

        $plan = PlanesPago::create([
            'nombre' => $request->nombre,
            'habilitado' => $request->habilitado ?? 0,
            'principal' => $request->principal ?? 0,
            'es_promocion' => $request->es_promocion ?? 0,
            'fecha_inicio_promocion' => $request->es_promocion ? $request->fecha_inicio_promocion : null,
            'fecha_fin_promocion' => $request->es_promocion ? $request->fecha_fin_promocion : null,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Plan de pago registrado correctamente.',
            'plan' => $plan
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');

        $query = PlanesPago::where('nombre', $nombre);

        if ($id && is_numeric($id) && $id > 0) {
            $query->where('id', '!=', $id);
        }

        return response()->json(['exists' => $query->exists()]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
        ]);

        $nombre = trim($request->input('nombre'));
        $id = $request->input('id');

        $id = is_numeric($id) && $id > 0 ? (int) $id : null;

        $exists = PlanesPago::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function planesModificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:planes_pagos,id',
            'nombre' => 'required|unique:planes_pagos,nombre,' . $request->id,
            'habilitado' => 'nullable|boolean',
            'principal' => 'nullable|boolean',
            'es_promocion' => 'nullable|boolean',
            'fecha_inicio_promocion' => 'nullable|date|required_if:es_promocion,1',
            'fecha_fin_promocion' => 'nullable|date|after:fecha_inicio_promocion|required_if:es_promocion,1',
        ]);

        $plan = PlanesPago::findOrFail($request->id);
        $plan->nombre = $request->nombre;
        $plan->habilitado = $request->habilitado ?? 0;
        $plan->principal = $request->principal ?? 0;
        $plan->es_promocion = $request->es_promocion ?? 0;
        $plan->fecha_inicio_promocion = $request->es_promocion ? $request->fecha_inicio_promocion : null;
        $plan->fecha_fin_promocion = $request->es_promocion ? $request->fecha_fin_promocion : null;
        $plan->save();

        return response()->json([
            'success' => true,
            'msg' => 'Plan de pago actualizado correctamente.'
        ]);
    }

    public function planesEliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:planes_pagos,id'
        ]);

        $plan = PlanesPago::findOrFail($request->id);
        $plan->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Plan de pago eliminado correctamente.'
        ]);
    }
}
