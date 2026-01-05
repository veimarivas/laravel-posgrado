<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Programa;
use Illuminate\Http\Request;

class ProgramasController extends Controller
{
    public function buscarOCrear(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        $programa = Programa::firstOrCreate(
            ['nombre' => trim($request->nombre)],
            ['nombre' => trim($request->nombre)]
        );
        return response()->json(['id' => $programa->id]);
    }
}
