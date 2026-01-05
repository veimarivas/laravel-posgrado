<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use Illuminate\Http\Request;

class CargosController extends Controller
{
    public function cargosListar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Cargo::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        $cargos = $query->paginate(10)->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.cargos.partials.table-body', compact('cargos'))->render(),
                'pagination' => $cargos->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.cargos.listar', compact('cargos'));
    }

    public function cargosRegistrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:cargos,nombre',
        ]);

        $cargo = Cargo::create([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Cargo Registrada correctamente.!',
            'cargo' => $cargo
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $id = $request->input('id');

        $query = Cargo::where('nombre', $nombre);

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

        $exists = Cargo::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function cargosModificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cargos,id',
            'nombre' => 'required|unique:cargos,nombre,' . $request->id,
        ]);

        Cargo::where('id', $request->id)->update([
            'nombre' => $request->nombre
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Cargo actualizado correctamente.'
        ]);
    }

    public function cargosEliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cargos,id'
        ]);

        Cargo::where('id', $request->id)->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Cargo eliminado correctamente.'
        ]);
    }
}
