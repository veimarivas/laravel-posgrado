<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Sucursale;
use Illuminate\Http\Request;

class SucursalesController extends Controller
{
    public function porSede(Request $request)
    {
        return Sucursale::where('sede_id', $request->sede_id)->get(['id', 'nombre']);
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],
            'direccion' => 'required|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'sede_id' => 'required|exists:sedes,id',
        ]);

        $sucursal = Sucursale::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'color' => $request->color,
            'sede_id' => $request->sede_id,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Sucursal registrada correctamente.',
            'sucursal' => $sucursal
        ]);
    }

    public function modificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sucursales,id',
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],
            'direccion' => 'required|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'sede_id' => 'required|exists:sedes,id',
        ]);

        $sucursal = Sucursale::findOrFail($request->id);
        $sucursal->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'color' => $request->color,
            'sede_id' => $request->sede_id,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Sucursal actualizada correctamente.',
            'sucursal' => $sucursal
        ]);
    }

    public function eliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sucursales,id'
        ]);

        $sucursal = Sucursale::findOrFail($request->id);
        $sucursal->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Sucursal eliminada correctamente.'
        ]);
    }

    public function verificarNombre(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'sede_id' => 'required|exists:sedes,id',
        ]);

        $query = Sucursale::where('sede_id', $request->sede_id)
            ->where('nombre', $request->nombre);

        // Excluir la sucursal actual en caso de edición
        if ($request->has('id') && $request->id) {
            $query->where('id', '!=', $request->id);
        }

        return response()->json(['exists' => $query->exists()]);
    }
}
