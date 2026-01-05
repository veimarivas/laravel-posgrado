<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Fase;
use Illuminate\Http\Request;

class FasesController extends Controller
{
    public function fasesListar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Fase::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        $fases = $query->paginate(10);
        $fases->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.fases.partials.table-body', compact('fases'))->render(),
                'pagination' => $fases->links('pagination::bootstrap-5')->toHtml(),
                'total' => $fases->total(),
                'from' => $fases->firstItem(),
                'to' => $fases->lastItem()
            ]);
        }

        return view('admin.fases.listar', compact('fases'));
    }

    public function fasesRegistrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:fases,nombre',
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        // Obtener el siguiente número de fase
        $ultimo = Fase::max('n_fase');
        $n_fase = $ultimo ? $ultimo + 1 : 1;

        $fase = Fase::create([
            'n_fase' => $n_fase,
            'nombre' => $request->nombre,
            'color' => $request->color,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Fase registrada correctamente.',
            'fase' => $fase
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');

        $query = Fase::where('nombre', $nombre);

        if ($id && is_numeric($id) && $id > 0) {
            $query->where('id', '!=', $id);
        }

        return response()->json(['exists' => $query->exists()]);
    }

    public function fasesModificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:fases,id',
            'nombre' => 'required|unique:fases,nombre,' . $request->id,
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $fase = Fase::findOrFail($request->id);
        $fase->update([
            'nombre' => $request->nombre,
            'color' => $request->color,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Fase actualizada correctamente.'
        ]);
    }

    public function fasesEliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:fases,id'
        ]);

        $fase = Fase::findOrFail($request->id);

        // Verificar si hay ofertas académicas relacionadas
        if ($fase->ofertas_academicas()->exists()) {
            return response()->json([
                'success' => false,
                'msg' => 'No se puede eliminar la fase porque tiene ofertas académicas relacionadas.'
            ], 400);
        }

        $fase->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Fase eliminada correctamente.'
        ]);
    }
}
