<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Universidade;
use Illuminate\Http\Request;

class UniversidadesController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');

        $query = Universidade::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('sigla', 'like', "%{$search}%");
            });
        }

        $universidades = $query->orderBy('nombre')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.universidades.partials.table-body', compact('universidades'))->render(),
                'pagination' => $universidades->links('pagination::bootstrap-5')->toHtml(),
                'total' => $universidades->total(),
                'from' => $universidades->firstItem(),
                'to' => $universidades->lastItem()
            ]);
        }

        return view('admin.universidades.listar', compact('universidades'));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:universidades,nombre',
            'sigla' => 'required|unique:universidades,sigla',
        ]);

        $universidad = Universidade::create([
            'nombre' => $request->nombre,
            'sigla' => $request->sigla
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Universidad registrada correctamente.',
            'universidad' => $universidad
        ]);
    }

    public function verificar(Request $request)
    {
        $nombre = $request->input('nombre');
        $sigla = $request->input('sigla');
        $id = $request->input('id');

        $nombreExists = false;
        $siglaExists = false;

        if ($nombre) {
            $queryNombre = Universidade::where('nombre', $nombre);
            if ($id && is_numeric($id) && $id > 0) {
                $queryNombre->where('id', '!=', $id);
            }
            $nombreExists = $queryNombre->exists();
        }

        if ($sigla) {
            $querySigla = Universidade::where('sigla', $sigla);
            if ($id && is_numeric($id) && $id > 0) {
                $querySigla->where('id', '!=', $id);
            }
            $siglaExists = $querySigla->exists();
        }

        return response()->json([
            'nombre_exists' => $nombreExists,
            'sigla_exists' => $siglaExists
        ]);
    }

    public function verificarEdicion(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'sigla' => 'required|string',
        ]);

        $nombre = trim($request->input('nombre'));
        $sigla = trim($request->input('sigla'));
        $id = $request->input('id');

        $id = is_numeric($id) && $id > 0 ? (int) $id : null;

        $nombreExists = Universidade::where('nombre', $nombre)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        $siglaExists = Universidade::where('sigla', $sigla)
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json([
            'nombre_exists' => $nombreExists,
            'sigla_exists' => $siglaExists
        ]);
    }

    public function modificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:universidades,id',
            'nombre' => 'required|unique:universidades,nombre,' . $request->id,
            'sigla' => 'required|unique:universidades,sigla,' . $request->id,
        ]);

        $universidad = Universidade::findOrFail($request->id);
        $universidad->update([
            'nombre' => $request->nombre,
            'sigla' => $request->sigla
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Universidad actualizada correctamente.'
        ]);
    }

    public function eliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:universidades,id'
        ]);

        $universidad = Universidade::findOrFail($request->id);

        // Verificar si hay estudios relacionados
        if ($universidad->estudios()->exists()) {
            return response()->json([
                'success' => false,
                'msg' => 'No se puede eliminar la universidad porque tiene estudios relacionados.'
            ], 400);
        }

        $universidad->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Universidad eliminada correctamente.'
        ]);
    }
}
