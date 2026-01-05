<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tipo;
use Illuminate\Http\Request;

class TiposController extends Controller
{
    public function tiposListar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Tipo::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        $tipos = $query->orderBy('nombre')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.tipos.partials.table-body', compact('tipos'))->render(),
                'pagination' => (string) $tipos->links('pagination::bootstrap-5')
            ]);
        }

        return view('admin.tipos.listar', compact('tipos'));
    }

    public function tiposRegistrar(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|unique:tipos,nombre',
            ], [
                'nombre.required' => 'El nombre es obligatorio',
                'nombre.unique' => 'Este tipo ya está registrado'
            ]);

            Tipo::create($validated);

            return response()->json([
                'success' => true,
                'msg' => 'Tipo registrado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al registrar el tipo'
            ], 500);
        }
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');

        $exists = Tipo::where('nombre', $nombre)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');

        $exists = Tipo::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function tiposModificar(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:tipos,id',
                'nombre' => 'required|unique:tipos,nombre,' . $request->id,
            ], [
                'nombre.required' => 'El nombre es obligatorio',
                'nombre.unique' => 'Este tipo ya está registrado'
            ]);

            $tipo = Tipo::findOrFail($request->id);
            $tipo->update($validated);

            return response()->json([
                'success' => true,
                'msg' => 'Tipo actualizado exitosamente'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'Error de validación'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al actualizar el tipo'
            ], 500);
        }
    }

    public function tiposEliminar(Request $request)
    {
        try {
            $id = $request->get('id');
            $tipo = Tipo::findOrFail($id);

            // Verificar si hay posgrados relacionados
            if ($tipo->posgrados()->exists()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'No se puede eliminar el tipo porque tiene posgrados relacionados'
                ], 400);
            }

            $tipo->delete();

            return response()->json([
                'success' => true,
                'msg' => 'Tipo eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al eliminar el tipo'
            ], 500);
        }
    }
}
