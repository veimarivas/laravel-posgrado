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

        $query = PlanesPago::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        $planes = $query->paginate(10);
        $planes->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.planes.partials.table-body', compact('planes'))->render(),
                'pagination' => $planes->links('pagination::bootstrap-5')->toHtml(),
                'total' => $planes->total(),
                'from' => $planes->firstItem(),
                'to' => $planes->lastItem()
            ]);
        }

        return view('admin.planes.listar', compact('planes'));
    }

    public function planesRegistrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:planes_pagos,nombre',
        ]);

        $plan = PlanesPago::create([
            'nombre' => $request->nombre
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
        ]);

        $plan = PlanesPago::findOrFail($request->id);
        $plan->nombre = $request->nombre;
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
