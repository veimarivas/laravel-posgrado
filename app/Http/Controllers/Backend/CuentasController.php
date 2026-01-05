<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use Illuminate\Http\Request;

class CuentasController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');
        $query = Cuenta::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('correo', 'like', "%{$search}%");
            });
        }
        $cuentas = $query->paginate(10)->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.cuentas.partials.table-body', compact('cuentas'))->render(),
                'pagination' => $cuentas->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.cuentas.listar', compact('cuentas'));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'correo' => 'required|unique:cuentas,correo',
            'cantidad_sesiones'  => 'required',
        ]);

        $cuenta = Cuenta::create($request->only('correo', 'cantidad_sesiones'));

        return response()->json([
            'success' => true,
            'msg' => 'Cuenta registrada correctamente.',
            'cuenta' => $cuenta
        ]);
    }

    public function verificar(Request $request)
    {
        $correo = trim($request->input('correo'));

        $response = [
            'correo_exists' => false,
        ];

        if ($correo !== '') {
            $response['nombre_exists'] = Cuenta::where('correo', $correo)->exists();
        }

        return response()->json($response);
    }

    public function verificarEdicion(Request $request)
    {
        $correo = trim($request->input('correo'));
        $id = $request->input('id');

        $id = is_numeric($id) && $id > 0 ? (int) $id : null;

        $response = [
            'correo_exists' => false,
        ];

        if ($correo !== '') {
            $query = Cuenta::where('correo', $correo);
            if ($id) $query->where('id', '!=', $id);
            $response['correo_exists'] = $query->exists();
        }

        return response()->json($response);
    }

    public function modificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cuentas,id',
            'correo' => 'required',
            'cantidad_sesiones'  => 'required',
        ]);

        Cuenta::where('id', $request->id)->update($request->only('correo', 'cantidad_sesiones'));

        return response()->json([
            'success' => true,
            'msg' => 'Cuenta actualizada correctamente.'
        ]);
    }

    public function eliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cuentas,id'
        ]);

        Cuenta::where('id', $request->id)->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Cuenta eliminada correctamente.'
        ]);
    }
}
