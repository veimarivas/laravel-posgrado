<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Ciudade;
use Illuminate\Http\Request;

class CiudadesController extends Controller
{
    public function porCiudad(Request $request)
    {
        return Ciudade::where('departamento_id', $request->departamento_id)->get(['id', 'nombre']);
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        $ciudad = Ciudade::create([
            'nombre' => $request->nombre,
            'departamento_id' => $request->departamento_id,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Ciudad registrada correctamente.',
            'ciudad' => $ciudad
        ]);
    }

    public function modificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:ciudades,id',
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        $ciudad = Ciudade::findOrFail($request->id);
        $ciudad->update([
            'nombre' => $request->nombre,
            'departamento_id' => $request->departamento_id,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Ciudad actualizada correctamente.',
            'ciudad' => $ciudad
        ]);
    }

    public function eliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:ciudades,id'
        ]);

        $ciudad = Ciudade::findOrFail($request->id);
        $ciudad->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Ciudad eliminada correctamente.'
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        $query = Ciudade::where('departamento_id', $request->departamento_id)
            ->where('nombre', $request->nombre);

        if ($request->has('id') && $request->id) {
            $query->where('id', '!=', $request->id);
        }

        return response()->json(['exists' => $query->exists()]);
    }

    public function verificarNombreEdicion(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        $query = Ciudade::where('departamento_id', $request->departamento_id)
            ->where('nombre', $request->nombre);

        if ($request->has('id') && $request->id) {
            $query->where('id', '!=', $request->id);
        }

        return response()->json(['exists' => $query->exists()]);
    }
}
