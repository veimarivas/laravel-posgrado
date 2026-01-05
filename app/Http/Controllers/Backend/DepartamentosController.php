<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentosController extends Controller
{
    public function departamentosListar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Departamento::with('ciudades');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhereHas('ciudades', function ($subq) use ($search) {
                        $subq->where('nombre', 'like', "%{$search}%");
                    });
            });
        }

        $departamentos = $query->paginate(10)->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.departamentos.partials.table-body', compact('departamentos'))->render(),
                'pagination' => $departamentos->links('pagination::bootstrap-5')->toHtml(),
                'total' => $departamentos->total(),
                'from' => $departamentos->firstItem(),
                'to' => $departamentos->lastItem()
            ]);
        }

        return view('admin.departamentos.listar', compact('departamentos'));
    }

    public function departamentosRegistrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:departamentos,nombre',
        ]);

        $departamento = Departamento::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Departamento Registrado correctamente.!',
            'departamento' => $departamento
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');

        $query = Departamento::where('nombre', $nombre);

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

        $exists = Departamento::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function departamentosModificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:departamentos,id',
            'nombre' => 'required|unique:departamentos,nombre,' . $request->id,
        ]);

        $departamento = Departamento::find($request->id);
        $departamento->update([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Departamento actualizado correctamente.'
        ]);
    }

    public function departamentosEliminar(Request $request)
    {
        $id = $request->input('id');

        if (!$id && $request->isJson()) {
            $data = $request->json()->all();
            $id = $data['id'] ?? null;
        }

        if (!$id) {
            return response()->json([
                'success' => false,
                'msg' => 'ID de departamento no proporcionado'
            ], 400);
        }

        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'success' => false,
                'msg' => 'ID de departamento inválido'
            ], 400);
        }

        $departamento = Departamento::find($id);

        if (!$departamento) {
            return response()->json([
                'success' => false,
                'msg' => 'El departamento no existe'
            ], 404);
        }

        // Verificar si tiene ciudades
        if ($departamento->ciudades()->count() > 0) {
            return response()->json([
                'success' => false,
                'msg' => 'No se puede eliminar el departamento porque tiene ciudades asociadas. Elimine primero todas las ciudades asociadas a este departamento.'
            ], 400);
        }

        try {
            $departamento->delete();
            return response()->json([
                'success' => true,
                'msg' => 'Departamento eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar departamento ID ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al intentar eliminar el departamento. Por favor, inténtelo de nuevo.'
            ], 500);
        }
    }

    public function departamentosVer($id)
    {
        $departamento = Departamento::with('ciudades')->findOrFail($id);

        return response()->json([
            'departamento' => $departamento,
            'ciudades' => $departamento->ciudades
        ]);
    }
}
