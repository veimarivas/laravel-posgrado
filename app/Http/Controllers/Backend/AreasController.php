<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreasController extends Controller
{
    public function areasListar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Area::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        $areas = $query->orderBy('nombre')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.areas.partials.table-body', compact('areas'))->render(),
                'pagination' => (string) $areas->links('pagination::bootstrap-5')
            ]);
        }

        return view('admin.areas.listar', compact('areas'));
    }

    public function areasRegistrar(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|unique:areas,nombre',
            ], [
                'nombre.required' => 'El nombre es obligatorio',
                'nombre.unique' => 'Esta área ya está registrada'
            ]);

            Area::create($validated);

            return response()->json([
                'success' => true,
                'msg' => 'Área registrada exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al registrar el área'
            ], 500);
        }
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');

        $exists = Area::where('nombre', $nombre)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');

        $exists = Area::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function areasModificar(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:areas,id',
                'nombre' => 'required|unique:areas,nombre,' . $request->id,
            ], [
                'nombre.required' => 'El nombre es obligatorio',
                'nombre.unique' => 'Esta área ya está registrada'
            ]);

            $area = Area::findOrFail($request->id);
            $area->update($validated);

            return response()->json([
                'success' => true,
                'msg' => 'Área actualizada exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al actualizar el área'
            ], 500);
        }
    }

    public function areasEliminar(Request $request)
    {
        try {
            $id = $request->get('id');
            $area = Area::findOrFail($id);

            // Verificar si hay posgrados relacionados
            if ($area->posgrados()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'No se puede eliminar el área porque tiene posgrados relacionados'
                ], 400);
            }

            $area->delete();

            return response()->json([
                'success' => true,
                'msg' => 'Área eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al eliminar el área'
            ], 500);
        }
    }
}
