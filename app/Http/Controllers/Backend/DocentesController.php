<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Modulo;
use App\Models\Persona;
use Illuminate\Http\Request;

class DocentesController extends Controller
{
    // Verifica si una persona existe y su estado como docente
    public function verificarCarnet(Request $request)
    {
        $carnet = $request->carnet;
        $persona = Persona::where('carnet', $carnet)->first();

        if (!$persona) {
            return response()->json([
                'exists' => false,
                'is_docente' => false,
                'message' => 'La persona no está registrada.'
            ]);
        }

        $docente = Docente::where('persona_id', $persona->id)->first();

        return response()->json([
            'exists' => true,
            'is_docente' => (bool) $docente,
            'message' => $docente
                ? 'La persona ya es docente.'
                : 'La persona está registrada pero no es docente.',
            'persona' => [
                'id' => $persona->id,
                'nombre_completo' => trim("{$persona->apellido_paterno} {$persona->apellido_materno}, {$persona->nombres}"),
                'carnet' => $persona->carnet,
                'correo' => $persona->correo,
                'celular' => $persona->celular,
            ],
            'docente_id' => $docente?->id,
        ]);
    }

    // Registra a una persona existente como docente
    public function registrar(Request $request)
    {
        $request->validate(['persona_id' => 'required|exists:personas,id']);

        if (Docente::where('persona_id', $request->persona_id)->exists()) {
            return response()->json([
                'success' => false,
                'msg' => 'Esta persona ya está registrada como docente.'
            ], 422);
        }

        $docente = Docente::create(['persona_id' => $request->persona_id]);

        return response()->json([
            'success' => true,
            'msg' => 'Docente registrado correctamente.',
            'docente_id' => $docente->id
        ]);
    }

    // Registra nueva persona + docente
    // Registra nueva persona + docente
    public function registrarPersonaYDocente(Request $request)
    {
        $request->validate([
            'carnet' => 'required|unique:personas,carnet',
            'nombres' => 'required|string|max:255',
            'expedido' => 'nullable|in:Lp,Or,Pt,Cb,Ch,Tj,Be,Sc,Pn',
            'apellido_paterno' => 'nullable|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'sexo' => 'required|in:Hombre,Mujer',
            'estado_civil' => 'required|in:Soltero(a),Casado(a),Divorciado(a),Viudo(a)',
            'fecha_nacimiento' => 'nullable|date|before_or_equal:today', // 👈 Agregado
            'correo' => 'required|email|unique:personas,correo',
            'celular' => 'required|numeric',
            'telefono' => 'nullable|numeric', // 👈 Agregado
            'direccion' => 'nullable|string', // 👈 Agregado
            'ciudade_id' => 'required|exists:ciudades,id',
        ]);

        if (empty($request->apellido_paterno) && empty($request->apellido_materno)) {
            return response()->json([
                'success' => false,
                'errors' => ['apellidos' => ['Debe ingresar al menos un apellido.']]
            ], 422);
        }

        $persona = Persona::create($request->only([
            'carnet',
            'nombres',
            'expedido',
            'apellido_paterno',
            'apellido_materno',
            'sexo',
            'estado_civil',
            'fecha_nacimiento', // 👈
            'correo',
            'celular',
            'telefono',         // 👈
            'direccion',        // 👈
            'ciudade_id'
        ]));

        $docente = Docente::create(['persona_id' => $persona->id]);

        if ($request->has('estudios') && is_array($request->estudios)) {
            foreach ($request->estudios as $estudio) {
                if (!empty($estudio['grado']) && !empty($estudio['profesion']) && !empty($estudio['universidad'])) {
                    $persona->estudios()->create([
                        'grados_academico_id' => $estudio['grado'],
                        'profesione_id' => $estudio['profesion'],
                        'universidade_id' => $estudio['universidad'],
                        'estado' => 'Concluido',
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'msg' => 'Persona registrada y asignada como docente correctamente.',
            'docente_id' => $docente->id,
            'docente_nombre' => trim("{$persona->apellido_paterno} {$persona->apellido_materno}, {$persona->nombres}")
        ]);
    }

    // Asigna docente a un módulo
    public function asignarADocente(Request $request)
    {
        $request->validate([
            'modulo_id' => 'required|exists:modulos,id',
            'docente_id' => 'required|exists:docentes,id'
        ]);

        $modulo = Modulo::findOrFail($request->modulo_id);
        $modulo->docente_id = $request->docente_id;
        $modulo->save();

        $docente = $modulo->docente->persona;

        return response()->json([
            'success' => true,
            'docente_nombre' => trim("{$docente->apellido_paterno} {$docente->apellido_materno}, {$docente->nombres}"),
            'docente_id' => $docente->id,
        ]);
    }
}
