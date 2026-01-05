<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Profesione;
use Illuminate\Http\Request;

class ProfesionesController extends Controller
{
    public function profesionesListar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Profesione::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        $profesiones = $query->paginate(10)->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.profesiones.partials.table-body', compact('profesiones'))->render(),
                'pagination' => $profesiones->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.profesiones.listar', compact('profesiones'));
    }

    public function profesionesRegistrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:profesiones,nombre',
        ]);

        $profesion = Profesione::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Profesión Registrada correctamente.!',
            'profesion' => $profesion
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');

        $query = Profesione::where('nombre', $nombre);

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

        $exists = Profesione::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function profesionesModificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:profesiones,id',
            'nombre' => 'required|unique:profesiones,nombre,' . $request->id,
        ]);

        Profesione::where('id', $request->id)->update([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Profesión actualizada correctamente.'
        ]);
    }

    public function profesionesEliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:profesiones,id'
        ]);

        Profesione::where('id', $request->id)->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Profesión eliminada correctamente.'
        ]);
    }
}
