<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GradosAcademico;
use Illuminate\Http\Request;

class GradosAcademicosController extends Controller
{
    public function gradosListar(Request $request)
    {
        $search = $request->get('search', '');

        $query = GradosAcademico::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        $grados = $query->paginate(10)->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.grados.partials.table-body', compact('grados'))->render(),
                'pagination' => $grados->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.grados.listar', compact('grados'));
    }

    public function gradosRegistrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:grados_academicos,nombre',
        ]);

        $grado = GradosAcademico::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Grado académico Registrado correctamente.!',
            'grado' => $grado
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');

        $query = GradosAcademico::where('nombre', $nombre);

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

        $exists = GradosAcademico::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function gradosModificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:grados_academicos,id',
            'nombre' => 'required|unique:grados_academicos,nombre,' . $request->id,
        ]);

        GradosAcademico::where('id', $request->id)->update([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Grado Académico actualizado correctamente.'
        ]);
    }

    public function gradosEliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:grados_academicos,id'
        ]);

        GradosAcademico::where('id', $request->id)->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Grado Académico eliminado correctamente.'
        ]);
    }
}
