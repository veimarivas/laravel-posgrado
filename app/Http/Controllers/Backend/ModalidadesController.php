<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Modalidade;
use Illuminate\Http\Request;

class ModalidadesController extends Controller
{
    public function modalidadesListar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Modalidade::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        $modalidades = $query->paginate(10)->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.modalidades.partials.table-body', compact('modalidades'))->render(),
                'pagination' => $modalidades->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.modalidades.listar', compact('modalidades'));
    }

    public function modalidadesRegistrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:modalidades,nombre',
        ]);

        $modalidad = Modalidade::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Modalidad Registrada correctamente.!',
            'modalidad' => $modalidad
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');

        $query = Modalidade::where('nombre', $nombre);

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

        $exists = Modalidade::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function modalidadesModificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:modalidades,id',
            'nombre' => 'required|unique:modalidades,nombre,' . $request->id,
        ]);

        Modalidade::where('id', $request->id)->update([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Modalidad actualizada correctamente.'
        ]);
    }

    public function modalidadesEliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:modalidades,id'
        ]);

        Modalidade::where('id', $request->id)->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Modalidad eliminada correctamente.'
        ]);
    }
}
