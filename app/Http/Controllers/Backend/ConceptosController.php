<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Concepto;
use Illuminate\Http\Request;

class ConceptosController extends Controller
{
    public function conceptosListar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Concepto::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        $conceptos = $query->paginate(10)->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.conceptos.partials.table-body', compact('conceptos'))->render(),
                'pagination' => $conceptos->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.conceptos.listar', compact('conceptos'));
    }

    public function conceptosRegistrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:conceptos,nombre',
        ]);

        $concepto = Concepto::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Concepto Registrado correctamente.!',
            'concepto' => $concepto
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');

        $query = Concepto::where('nombre', $nombre);

        // Solo excluir si el ID es válido
        if ($id && is_numeric($id) && $id > 0) {
            $query->where('id', '!=', $id);
        }

        return response()->json(['exists' => $query->exists()]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            // 'id' puede ser nulo o numérico
        ]);

        $nombre = trim($request->input('nombre'));
        $id = $request->input('id');

        // Convertir a entero si es numérico, sino null
        $id = is_numeric($id) && $id > 0 ? (int) $id : null;

        $exists = Concepto::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function conceptosModificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:conceptos,id',
            'nombre' => 'required|unique:conceptos,nombre,' . $request->id,
        ]);

        Concepto::where('id', $request->id)->update([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Concepto actualizada correctamente.'
        ]);
    }

    public function conceptosEliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:conceptos,id'
        ]);

        Concepto::where('id', $request->id)->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Concepto eliminada correctamente.'
        ]);
    }
}
