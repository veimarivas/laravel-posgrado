<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sede;
use Illuminate\Http\Request;

class SedesController extends Controller
{
    public function sedesListar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Sede::with('sucursales');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhereHas('sucursales', function ($subq) use ($search) {
                        $subq->where('nombre', 'like', "%{$search}%");
                    });
            });
        }

        $sedes = $query->paginate(10)->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.sedes.partials.table-body', compact('sedes'))->render(),
                'pagination' => $sedes->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.sedes.listar', compact('sedes'));
    }

    public function sedesRegistrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:sedes,nombre',
        ]);

        $sede = Sede::create([
            'nombre' => $request->nombre,
            'ciudade_id' => $request->ciudade_id
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Sede Registrada correctamente.!',
            'sede' => $sede
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');

        $query = Sede::where('nombre', $nombre);

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

        $exists = Sede::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function sedesModificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sedes,id',
            'nombre' => 'required|unique:sedes,nombre,' . $request->id,
            // Si ciudade_id es obligatorio, descomenta esta línea
            // 'ciudade_id' => 'required|exists:ciudades,id'
        ]);

        $sede = Sede::find($request->id);
        $sede->update([
            'nombre' => $request->nombre,
            // Solo actualizar ciudade_id si está presente en la solicitud
            'ciudade_id' => $request->has('ciudade_id') ? $request->ciudade_id : $sede->ciudade_id
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Sede actualizada correctamente.'
        ]);
    }

    public function sedesEliminar(Request $request)
    {
        // Obtener el ID de manera más robusta
        $id = $request->input('id');

        // Si no viene por input, intentar desde JSON
        if (!$id && $request->isJson()) {
            $data = $request->json()->all();
            $id = $data['id'] ?? null;
        }

        if (!$id) {
            return response()->json([
                'success' => false,
                'msg' => 'ID de sede no proporcionado'
            ], 400);
        }

        // Validar que el ID sea numérico
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'success' => false,
                'msg' => 'ID de sede inválido'
            ], 400);
        }

        // Buscar la sede
        $sede = Sede::find($id);

        if (!$sede) {
            return response()->json([
                'success' => false,
                'msg' => 'La sede no existe'
            ], 404);
        }

        // Verificar si tiene sucursales
        if ($sede->sucursales()->count() > 0) {
            return response()->json([
                'success' => false,
                'msg' => 'No se puede eliminar la sede porque tiene sucursales asociadas. Elimine primero todas las sucursales asociadas a esta sede.'
            ], 400);
        }

        try {
            $sede->delete();
            return response()->json([
                'success' => true,
                'msg' => 'Sede eliminada correctamente.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar sede ID ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al intentar eliminar la sede. Por favor, inténtelo de nuevo.'
            ], 500);
        }
    }

    // Agregar este método en SedesController
    public function ver($id)
    {
        $sede = Sede::with('sucursales')->findOrFail($id);

        return response()->json([
            'sede' => $sede,
            'sucursales' => $sede->sucursales
        ]);
    }
}
