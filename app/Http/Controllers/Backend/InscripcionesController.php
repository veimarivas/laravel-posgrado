<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Concepto;
use App\Models\Cuota;
use App\Models\Detalle;
use App\Models\Estudiante;
use App\Models\Inscripcione;
use App\Models\Matriculacione;
use App\Models\OfertasAcademica;
use App\Models\Pago;
use App\Models\PagosCuota;
use App\Models\Persona;
use App\Models\PlanesConcepto;
use App\Models\PlanesPago;
use App\Models\TrabajadoresCargo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InscripcionesController extends Controller
{
    public function verificarCarnet(Request $request)
    {
        $carnet = $request->carnet;
        $persona = Persona::where('carnet', $carnet)->first();

        if (!$persona) {
            return response()->json([
                'exists' => false,
                'is_student' => false,
                'message' => 'La persona no está registrada.'
            ]);
        }

        $estudiante = Estudiante::where('persona_id', $persona->id)->first();
        return response()->json([
            'exists' => true,
            'is_student' => !!$estudiante,
            'persona' => [
                'id' => $persona->id,
                'nombre_completo' => trim("{$persona->apellido_paterno} {$persona->apellido_materno}, {$persona->nombres}")
            ]
        ]);
    }

    public function registrar(Request $request)
    {
        \Log::info('=== INICIANDO REGISTRO DE INSCRIPCIÓN MEJORADO ===');

        $user = Auth::user();

        // Validación actualizada
        $request->validate([
            'oferta_id' => 'required|exists:ofertas_academicas,id',
            'estudiante_id' => 'required|exists:estudiantes,id',
            'estado' => 'required|in:Pre-Inscrito,Inscrito',
            'planes_pago_id' => 'required|exists:planes_pagos,id',
            'adelanto_bs' => 'nullable|numeric|min:0',
        ]);

        $oferta = OfertasAcademica::findOrFail($request->oferta_id);

        // ✅ Validación: Verificar si ya está inscrito/pre-inscrito
        $inscripcionExistente = Inscripcione::where('ofertas_academica_id', $oferta->id)
            ->where('estudiante_id', $request->estudiante_id)
            ->whereIn('estado', ['Pre-Inscrito', 'Inscrito'])
            ->first();

        if ($inscripcionExistente) {
            return response()->json([
                'success' => false,
                'msg' => 'Esta persona ya está inscrita o pre-inscrita en esta oferta académica.'
            ], 422);
        }

        // ✅ Validación: Verificar que el plan pertenezca a la oferta
        $planExisteEnOferta = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
            ->where('planes_pago_id', $request->planes_pago_id)
            ->exists();

        if (!$planExisteEnOferta) {
            return response()->json([
                'success' => false,
                'msg' => 'El plan de pago seleccionado no pertenece a esta oferta académica.'
            ], 422);
        }

        // ✅ Obtener usuario autenticado
        if (!$user || !$user->persona?->trabajador) {
            return response()->json([
                'success' => false,
                'msg' => 'El usuario actual no está asociado a un trabajador. Contacte al administrador.'
            ], 422);
        }

        $trabajadorCargo = $user->persona->trabajador->trabajadores_cargos()
            ->where('estado', 'Vigente')
            ->latest('fecha_ingreso')
            ->firstOrFail();

        if (!$trabajadorCargo) {
            return response()->json([
                'success' => false,
                'msg' => 'No tiene un cargo vigente asignado. No se puede registrar la inscripción.'
            ], 422);
        }

        // ✅ Registrar la inscripción
        $inscripcionData = [
            'ofertas_academica_id' => $oferta->id,
            'estudiante_id' => $request->estudiante_id,
            'trabajadores_cargo_id' => $trabajadorCargo->id,
            'estado' => $request->estado,
            'fecha_registro' => now(),
            'planes_pago_id' => $request->planes_pago_id,
            'adelanto_bs' => $request->adelanto_bs ?? null,
        ];

        // ✅ Crear la inscripción
        $inscripcion = Inscripcione::create($inscripcionData);

        // ✅ Solo para "Inscrito": generar cuotas y matrículas (esto se manejará en confirmarCuotas)
        // ✅ Para "Pre-Inscrito": solo guardar la inscripción sin cuotas ni matrículas

        return response()->json([
            'success' => true,
            'msg' => $request->estado === 'Inscrito'
                ? 'Inscripción registrada. Por favor, confirme las cuotas para completar el proceso.'
                : 'Pre-inscripción registrada exitosamente. El estudiante puede ser convertido a inscrito posteriormente.',
            'inscripcion_id' => $inscripcion->id,
            'estado' => $request->estado,
        ]);
    }

    public function verificarInscripcionExistente(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'oferta_id' => 'required|exists:ofertas_academicas,id',
        ]);

        $existe = Inscripcione::where('estudiante_id', $request->estudiante_id)
            ->where('ofertas_academica_id', $request->oferta_id)
            ->whereIn('estado', ['Pre-Inscrito', 'Inscrito'])
            ->exists();

        return response()->json(['exists' => $existe]);
    }


    public function generarCuotasPreview(Request $request)
    {
        $request->validate([
            'oferta_id' => 'required|exists:ofertas_academicas,id',
            'planes_pago_id' => 'required|exists:planes_pagos,id',
        ]);

        $oferta = OfertasAcademica::findOrFail($request->oferta_id);

        // Verificar que el plan de pago pertenezca a la oferta
        $planExisteEnOferta = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
            ->where('planes_pago_id', $request->planes_pago_id)
            ->exists();

        if (!$planExisteEnOferta) {
            return response()->json([
                'success' => false,
                'msg' => 'El plan de pago seleccionado no pertenece a esta oferta académica.'
            ], 422);
        }

        $planesConceptos = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
            ->where('planes_pago_id', $request->planes_pago_id)
            ->with('concepto')
            ->get();

        $cuotasPreview = [];
        foreach ($planesConceptos as $pc) {
            $montoTotal = $pc->pago_bs;
            $nCuotas = $pc->n_cuotas;

            // Calcular el monto base por cuota (redondeado hacia abajo)
            $montoBase = floor($montoTotal / $nCuotas);
            // Calcular el resto
            $resto = $montoTotal - ($montoBase * $nCuotas);

            $fechaBase = now();

            for ($i = 1; $i <= $nCuotas; $i++) {
                $fechaPago = $fechaBase->copy()->addMonths($i - 1)->format('Y-m-d');
                // Si es la última cuota, sumar el resto
                $montoPorCuota = $i == $nCuotas ? $montoBase + $resto : $montoBase;

                $cuotasPreview[] = [
                    'nombre' => "Cuota {$i} - {$pc->concepto->nombre}",
                    'n_cuota' => $i,
                    'pago_total_bs' => $montoPorCuota, // 👈 Monto entero por cuota
                    'pago_pendiente_bs' => $montoPorCuota,
                    'descuento_bs' => 0,
                    'fecha_pago' => $fechaPago,
                    'pago_terminado' => false,
                    'concepto_id' => $pc->concepto_id,
                    'concepto_nombre' => $pc->concepto->nombre,
                    'planes_concepto_id' => $pc->id,
                    'n_cuotas' => $pc->n_cuotas,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'cuotas_preview' => $cuotasPreview,
        ]);
    }

    public function confirmarCuotas(Request $request)
    {
        $request->validate([
            'oferta_id' => 'required|exists:ofertas_academicas,id',
            'estudiante_id' => 'required|exists:estudiantes,id',
            'planes_pago_id' => 'required|exists:planes_pagos,id',
            'estado' => 'required|in:Inscrito', // Solo Inscrito
            'cuotas_data' => 'required|array|min:1',
            'cuotas_data.*.concepto_id' => 'required|exists:conceptos,id',
            'cuotas_data.*.n_cuota' => 'required|integer|min:1',
            'cuotas_data.*.monto_bs' => 'required|numeric|min:0',
            'cuotas_data.*.fecha_pago' => 'required|date',
        ]);

        $oferta = OfertasAcademica::findOrFail($request->oferta_id);

        // Verificar que el plan de pago pertenece a la oferta
        $planExisteEnOferta = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
            ->where('planes_pago_id', $request->planes_pago_id)
            ->exists();

        if (!$planExisteEnOferta) {
            return response()->json([
                'success' => false,
                'msg' => 'El plan de pago no pertenece a esta oferta.'
            ], 422);
        }

        // Buscar o crear la inscripción
        $inscripcion = Inscripcione::where([
            ['ofertas_academica_id', $request->oferta_id],
            ['estudiante_id', $request->estudiante_id],
            ['planes_pago_id', $request->planes_pago_id],
        ])->first();

        if (!$inscripcion) {
            $user = Auth::user();
            $trabajadorCargo = $user->persona->trabajador->trabajadores_cargos()
                ->where('estado', 'Vigente')
                ->first();

            $inscripcion = Inscripcione::create([
                'ofertas_academica_id' => $request->oferta_id,
                'estudiante_id' => $request->estudiante_id,
                'trabajadores_cargo_id' => $trabajadorCargo->id,
                'estado' => 'Inscrito',
                'fecha_registro' => now(),
                'planes_pago_id' => $request->planes_pago_id,
            ]);
        } else {
            // Actualizar estado si ya existe como Pre-Inscrito
            $inscripcion->estado = 'Inscrito';
            $inscripcion->save();
        }

        // Registrar cuotas
        foreach ($request->cuotas_data as $cuotaData) {
            $concepto = Concepto::findOrFail($cuotaData['concepto_id']);

            Cuota::create([
                'nombre' => "Cuota {$cuotaData['n_cuota']} - {$concepto->nombre}",
                'n_cuota' => $cuotaData['n_cuota'],
                'pago_total_bs' => $cuotaData['monto_bs'],
                'pago_pendiente_bs' => $cuotaData['monto_bs'],
                'descuento_bs' => 0,
                'fecha_pago' => $cuotaData['fecha_pago'],
                'pago_terminado' => 'no',
                'inscripcione_id' => $inscripcion->id,
            ]);
        }

        // Matricular en todos los módulos (solo para Inscrito)
        foreach ($oferta->modulos as $modulo) {
            Matriculacione::firstOrCreate([
                'inscripcione_id' => $inscripcion->id,
                'modulo_id' => $modulo->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Inscripción completada exitosamente. Cuotas y matrículas registradas correctamente.'
        ]);
    }

    public function convertirPreInscrito(Request $request, $inscripcionId = null)
    {
        \Log::info('=== CONVERTIR PRE-INSCRITO INICIADO ===');
        \Log::info('Datos recibidos:', $request->all());
        \Log::info('Parámetro de ruta insercionId:', ['inscripcionId' => $inscripcionId]);

        // Si viene como parámetro de ruta, usarlo
        if ($inscripcionId) {
            $request->merge(['inscripcion_id' => $inscripcionId]);
        }

        $request->validate([
            'inscripcion_id' => 'required|exists:inscripciones,id',
            'oferta_id' => 'required|exists:ofertas_academicas,id',
            'planes_pago_id' => 'required|exists:planes_pagos,id',
        ]);

        $inscripcionId = $request->inscripcion_id;
        $ofertaId = $request->oferta_id;
        $planPagoId = $request->planes_pago_id;

        \Log::info('IDs procesados:', [
            'inscripcion_id' => $inscripcionId,
            'oferta_id' => $ofertaId,
            'planes_pago_id' => $planPagoId
        ]);

        // Buscar la inscripción
        $inscripcion = Inscripcione::with(['ofertaAcademica.modulos', 'estudiante'])
            ->where('id', $inscripcionId)
            ->first();

        if (!$inscripcion) {
            return response()->json([
                'success' => false,
                'msg' => 'La inscripción no existe en el sistema.'
            ], 422);
        }

        // Verificar que sea Pre-Inscrito
        if ($inscripcion->estado !== 'Pre-Inscrito') {
            return response()->json([
                'success' => false,
                'msg' => 'Solo se pueden convertir pre-inscripciones. Estado actual: ' . $inscripcion->estado
            ], 422);
        }

        // Verificar que pertenezca a la oferta indicada
        if ($inscripcion->ofertas_academica_id != $ofertaId) {
            return response()->json([
                'success' => false,
                'msg' => 'La inscripción no pertenece a la oferta académica indicada.'
            ], 422);
        }

        try {
            DB::beginTransaction();

            $oferta = $inscripcion->ofertaAcademica;

            // 2. Verificar que el plan pertenece a la oferta
            $planExisteEnOferta = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
                ->where('planes_pago_id', $planPagoId)
                ->exists();

            if (!$planExisteEnOferta) {
                return response()->json([
                    'success' => false,
                    'msg' => 'El plan de pago no pertenece a esta oferta académica.'
                ], 422);
            }

            // 3. Obtener los conceptos del plan
            $planesConceptos = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
                ->where('planes_pago_id', $planPagoId)
                ->with('concepto')
                ->get();

            if ($planesConceptos->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'No se encontraron conceptos para este plan de pago.'
                ], 422);
            }

            // 4. Cambiar estado a Inscrito
            $inscripcion->estado = 'Inscrito';
            $inscripcion->fecha_registro = now();
            $inscripcion->save();

            // 5. Generar cuotas basadas en los conceptos del plan
            $cuotaNumero = 1;
            foreach ($planesConceptos as $pc) {
                $montoTotal = $pc->pago_bs;
                $nCuotas = $pc->n_cuotas;

                // Calcular monto por cuota (redondeado)
                $montoBase = floor($montoTotal / $nCuotas * 100) / 100; // Redondear a 2 decimales
                $resto = round($montoTotal - ($montoBase * $nCuotas), 2);

                // Fecha base para las cuotas (usar fecha de inicio del programa o hoy)
                $fechaBase = $oferta->fecha_inicio_programa ?: now();

                for ($i = 1; $i <= $nCuotas; $i++) {
                    $fechaPago = $fechaBase->copy()->addMonths($i - 1)->format('Y-m-d');
                    // Si es la última cuota, sumar el resto
                    $montoPorCuota = $i == $nCuotas ? round($montoBase + $resto, 2) : $montoBase;

                    Cuota::create([
                        'nombre' => "Cuota {$i} - {$pc->concepto->nombre}",
                        'n_cuota' => $cuotaNumero,
                        'pago_total_bs' => $montoPorCuota,
                        'pago_pendiente_bs' => $montoPorCuota,
                        'descuento_bs' => 0,
                        'fecha_pago' => $fechaPago,
                        'pago_terminado' => 'no',
                        'inscripcione_id' => $inscripcion->id,
                    ]);
                    $cuotaNumero++;
                }
            }

            // 6. Matricular en todos los módulos de la oferta
            foreach ($oferta->modulos as $modulo) {
                Matriculacione::create([
                    'inscripcione_id' => $inscripcion->id,
                    'modulo_id' => $modulo->id,
                    'nota_regular' => 0,
                    'nota_nivelacion' => 0,
                ]);
            }

            // 7. Si hay adelanto registrado, aplicarlo a la primera cuota
            if ($inscripcion->adelanto_bs && $inscripcion->adelanto_bs > 0) {
                // Crear pago por adelanto
                $pagoAdelanto = Pago::create([
                    'recibo' => $this->generarRecibo(),
                    'pago_bs' => $inscripcion->adelanto_bs,
                    'descuento_bs' => 0,
                    'fecha_pago' => now(),
                    'tipo_pago' => 'efectivo',
                    'observacion' => 'Adelanto pre-inscripción a inscripción',
                ]);

                // Aplicar el adelanto a la primera cuota
                $primeraCuota = Cuota::where('inscripcione_id', $inscripcion->id)
                    ->orderBy('n_cuota', 'asc')
                    ->first();

                if ($primeraCuota) {
                    $montoAplicar = min($inscripcion->adelanto_bs, $primeraCuota->pago_pendiente_bs);
                    $primeraCuota->pago_pendiente_bs = round($primeraCuota->pago_pendiente_bs - $montoAplicar, 2);
                    $primeraCuota->pago_terminado = $primeraCuota->pago_pendiente_bs <= 0.01 ? 'si' : 'no';
                    $primeraCuota->save();

                    // Registrar relación pago-cuota
                    PagosCuota::create([
                        'cuota_id' => $primeraCuota->id,
                        'pago_id' => $pagoAdelanto->id,
                        'pago_bs' => $montoAplicar
                    ]);
                }
            }

            // 8. Registrar en observaciones
            $observacionActual = $inscripcion->observacion ?? '';
            $inscripcion->observacion = $observacionActual .
                " | Convertido a Inscrito el " . now()->format('d/m/Y H:i') .
                " por " . Auth::user()->name;
            $inscripcion->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Pre-inscripción convertida a inscripción exitosamente. Se generaron ' . ($cuotaNumero - 1) . ' cuotas y ' . $oferta->modulos->count() . ' matrículas.',
                'inscripcion_id' => $inscripcion->id,
                'estudiante' => $inscripcion->estudiante->persona->nombre_completo ?? 'Estudiante'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al convertir pre-inscrito: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al convertir la pre-inscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    public function listarPorOferta(OfertasAcademica $oferta)
    {
        $inscripciones = Inscripcione::with([
            'estudiante.persona',
            'planesPago'
        ])->where('ofertas_academica_id', $oferta->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Cargar datos necesarios para el modal
        $planesPagos = PlanesPago::all();
        $conceptos = Concepto::all();

        return view('admin.inscripciones.listar', compact(
            'oferta',
            'inscripciones',
            'planesPagos',
            'conceptos'
        ));
    }

    public function convertirAPagado(Request $request)
    {
        $request->validate([
            'inscripcion_id' => 'required|exists:inscripciones,id,estado,Pre-Inscrito',
            'planes_pago_id' => 'required|exists:planes_pagos,id',
        ]);

        $inscripcion = Inscripcione::with('ofertaAcademica')->findOrFail($request->inscripcion_id);
        $oferta = $inscripcion->ofertaAcademica;

        // Verificar que el plan pertenezca a la oferta
        $planExiste = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
            ->where('planes_pago_id', $request->planes_pago_id)
            ->exists();

        if (!$planExiste) {
            return response()->json([
                'success' => false,
                'msg' => 'El plan de pago no pertenece a esta oferta.'
            ], 422);
        }

        // Actualizar estado
        $inscripcion->estado = 'Inscrito';
        $inscripcion->planes_pago_id = $request->planes_pago_id;
        $inscripcion->save();

        // Generar cuotas (reutilizando lógica de confirmarCuotas)
        $planesConceptos = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
            ->where('planes_pago_id', $request->planes_pago_id)
            ->with('concepto')
            ->get();

        foreach ($planesConceptos as $pc) {
            $montoTotal = $pc->pago_bs;
            $nCuotas = $pc->n_cuotas;
            $montoBase = floor($montoTotal / $nCuotas);
            $resto = $montoTotal - ($montoBase * $nCuotas);

            for ($i = 1; $i <= $nCuotas; $i++) {
                $montoPorCuota = $i == $nCuotas ? $montoBase + $resto : $montoBase;
                $fechaPago = now()->copy()->addMonths($i - 1)->format('Y-m-d');

                Cuota::create([
                    'nombre' => "Cuota {$i} - {$pc->concepto->nombre}",
                    'n_cuota' => $i,
                    'pago_total_bs' => $montoPorCuota,
                    'pago_pendiente_bs' => $montoPorCuota,
                    'descuento_bs' => 0,
                    'fecha_pago' => $fechaPago,
                    'pago_terminado' => 'no',
                    'inscripcione_id' => $inscripcion->id,
                ]);
            }
        }

        // Matricular en módulos
        foreach ($oferta->modulos as $modulo) {
            Matriculacione::firstOrCreate([
                'inscripcione_id' => $inscripcion->id,
                'modulo_id' => $modulo->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Pre-inscrito convertido a inscrito con éxito. Cuotas y matrícula generadas.'
        ]);
    }

    // Obtener módulos y notas
    public function modulosNotas(Inscripcione $inscripcion)
    {
        $matriculaciones = $inscripcion->matriculaciones()->with('modulo')->get();
        return response()->json($matriculaciones);
    }

    // Registrar nota
    public function registrarNota(Request $request, Matriculacione $matriculacion)
    {
        $request->validate([
            'nota_regular' => 'nullable|numeric|min:0|max:100',
            'nota_nivelacion' => 'nullable|numeric|min:0|max:100',
        ]);

        $matriculacion->update($request->only(['nota_regular', 'nota_nivelacion']));

        return response()->json([
            'success' => true,
            'msg' => 'Nota actualizada correctamente.'
        ]);
    }

    // Obtener cuotas
    public function cuotas(Inscripcione $inscripcion)
    {
        $cuotas = $inscripcion->cuotas; // Asegúrate de tener la relación `cuotas()` en el modelo Inscripcione
        return response()->json($cuotas);
    }

    public function registrarPago(Request $request)
    {
        // VALIDACIÓN: cuotas_seleccionadas es opcional
        $request->validate([
            'pago_bs' => 'required|numeric|min:0.01',
            'descuento_bs' => 'nullable|numeric|min:0',
            'fecha_pago' => 'required|date',
            'tipo_pago' => 'required|in:efectivo,qr,parcial',
            'detalle_efectivo' => 'nullable|required_if:tipo_pago,parcial|numeric|min:0',
            'detalle_qr' => 'nullable|required_if:tipo_pago,parcial|numeric|min:0',
            'inscripcion_id' => 'required|exists:inscripciones,id', // Obligatorio
            'cuotas_seleccionadas' => 'nullable|array',
            'cuotas_seleccionadas.*.cuota_id' => 'required_with:cuotas_seleccionadas|exists:cuotas,id',
            'cuotas_seleccionadas.*.monto' => 'required_with:cuotas_seleccionadas|numeric|min:0.01',
        ]);

        // Convertir JSON a array si viene como string
        if (is_string($request->cuotas_seleccionadas)) {
            $request->merge([
                'cuotas_seleccionadas' => json_decode($request->cuotas_seleccionadas, true) ?? []
            ]);
        }

        $inscripcionId = $request->inscripcion_id;
        $cuotasSeleccionadas = $request->cuotas_seleccionadas ?? [];
        $montoTotalPago = $request->pago_bs;
        $descuento = $request->descuento_bs ?? 0;
        $montoNeto = $montoTotalPago - $descuento;

        // === CASO 1: Cuotas seleccionadas explícitamente ===
        if (!empty($cuotasSeleccionadas)) {
            $cuotas = [];
            foreach ($cuotasSeleccionadas as $item) {
                $cuota = Cuota::with('inscripcion')->findOrFail($item['cuota_id']);
                if ($cuota->pago_terminado === 'si') {
                    return response()->json(['success' => false, 'msg' => 'Una de las cuotas ya está pagada.'], 422);
                }
                if ($cuota->inscripcion->id !== $inscripcionId) {
                    return response()->json(['success' => false, 'msg' => 'Todas las cuotas deben pertenecer a la misma inscripción.'], 422);
                }
                $cuotas[] = $cuota;
            }

            // Validar secuencia: Matrícula → Colegiatura → Certificación
            $nombres = collect($cuotas)->pluck('nombre')->map(fn($n) => strtolower($n));
            $pagaMatricula = $nombres->contains(fn($n) => str_contains($n, 'matricula'));
            $pagaColegiatura = $nombres->contains(fn($n) => str_contains($n, 'colegiatura'));
            $pagaCertificacion = $nombres->contains(fn($n) => str_contains($n, 'certificacion'));

            // IDs de cuotas que se están pagando ahora
            $cuotasPagandoIds = array_column($cuotasSeleccionadas, 'cuota_id');

            if ($pagaColegiatura) {
                $matriculaPendiente = Cuota::where('inscripcione_id', $inscripcionId)
                    ->where('pago_terminado', 'no')
                    ->whereRaw('LOWER(nombre) LIKE ?', ['%matricula%'])
                    ->whereNotIn('id', $cuotasPagandoIds)
                    ->exists();
                if ($matriculaPendiente) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'cuotas_seleccionadas' => ['No se puede pagar Colegiatura sin Matrícula.']
                    ]);
                }
            }

            if ($pagaCertificacion) {
                $colegiaturaPendiente = Cuota::where('inscripcione_id', $inscripcionId)
                    ->where('pago_terminado', 'no')
                    ->whereRaw('LOWER(nombre) LIKE ?', ['%colegiatura%'])
                    ->whereNotIn('id', $cuotasPagandoIds)
                    ->exists();
                if ($colegiaturaPendiente) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'cuotas_seleccionadas' => ['No se puede pagar Certificación sin Colegiatura.']
                    ]);
                }
            }

            // Validar que el monto coincida
            $montoSeleccionado = collect($cuotasSeleccionadas)->sum('monto');
            if (abs($montoNeto - $montoSeleccionado) > 0.01) {
                return response()->json(['success' => false, 'msg' => 'El monto total no coincide con la suma de los montos seleccionados.'], 422);
            }
        } else {
            // === CASO 2: NO se seleccionaron cuotas → distribuir automáticamente ===
            $cuotasPendientes = Cuota::where('inscripcione_id', $inscripcionId)
                ->where('pago_terminado', 'no')
                ->orderByRaw("CASE 
                WHEN LOWER(nombre) LIKE '%matricula%' THEN 1
                WHEN LOWER(nombre) LIKE '%colegiatura%' THEN 2
                WHEN LOWER(nombre) LIKE '%certificacion%' THEN 3
                ELSE 4
            END")
                ->orderBy('fecha_pago')
                ->get();

            if ($cuotasPendientes->isEmpty()) {
                return response()->json(['success' => false, 'msg' => 'No hay cuotas pendientes para aplicar el pago.'], 422);
            }

            $montoRestante = $montoNeto;
            $cuotasSeleccionadas = []; // Reiniciar

            foreach ($cuotasPendientes as $cuota) {
                if ($montoRestante <= 0) break;
                $montoAplicar = min($montoRestante, $cuota->pago_pendiente_bs);
                $cuotasSeleccionadas[] = [
                    'cuota_id' => $cuota->id,
                    'monto' => $montoAplicar
                ];
                $montoRestante -= $montoAplicar;
            }

            // Si hay sobrepago, aplicarlo a la última cuota (opcional)
            if ($montoRestante > 0.01 && !empty($cuotasSeleccionadas)) {
                $ultima = &$cuotasSeleccionadas[count($cuotasSeleccionadas) - 1];
                $ultima['monto'] += $montoRestante;
            }
        }

        // === CREAR EL PAGO PRINCIPAL ===
        $pago = Pago::create([
            'recibo' => $this->generarRecibo(),
            'pago_bs' => $request->pago_bs,
            'descuento_bs' => $request->descuento_bs ?? 0,
            'fecha_pago' => $request->fecha_pago,
            'tipo_pago' => $request->tipo_pago,
        ]);

        // === REGISTRAR DETALLES PARCIALES (si aplica) ===
        if ($request->tipo_pago === 'parcial') {
            if ($request->detalle_efectivo > 0) {
                Detalle::create([
                    'pago_id' => $pago->id,
                    'pago_bs' => $request->detalle_efectivo,
                    'tipo_pago' => 'efectivo'
                ]);
            }
            if ($request->detalle_qr > 0) {
                Detalle::create([
                    'pago_id' => $pago->id,
                    'pago_bs' => $request->detalle_qr,
                    'tipo_pago' => 'qr'
                ]);
            }
        }

        // === APLICAR PAGO A CADA CUOTA Y ACTUALIZAR SALDOS ===
        foreach ($cuotasSeleccionadas as $item) {
            $cuota = Cuota::find($item['cuota_id']);
            $monto = $item['monto'];

            if ($monto <= 0) continue;

            // Asegurar que no quede saldo negativo
            $cuota->pago_pendiente_bs = max(0, $cuota->pago_pendiente_bs - $monto);
            if ($cuota->pago_pendiente_bs <= 0) {
                $cuota->pago_terminado = 'si';
            }
            $cuota->save();

            // Registrar relación pago-cuota
            PagosCuota::create([
                'cuota_id' => $cuota->id,
                'pago_id' => $pago->id,
                'pago_bs' => $monto
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Pago registrado correctamente.',
            'recibo' => $pago->recibo
        ]);
    }

    private function validarSecuenciaCuotas($cuotas, $cuotasSeleccionadas)
    {
        $nombres = collect($cuotas)->pluck('nombre')->map(fn($n) => strtolower($n));
        $pagaMatricula = $nombres->contains(fn($n) => str_contains($n, 'matricula'));
        $pagaColegiatura = $nombres->contains(fn($n) => str_contains($n, 'colegiatura'));
        $pagaCertificacion = $nombres->contains(fn($n) => str_contains($n, 'certificacion'));

        $inscripcionId = $cuotas[0]->inscripcion->id;
        $cuotasPagandoIds = array_column($cuotasSeleccionadas, 'cuota_id');

        if ($pagaColegiatura) {
            $matriculaPendiente = Cuota::where('inscripcione_id', $inscripcionId)
                ->where('pago_terminado', 'no')
                ->whereRaw('LOWER(nombre) LIKE ?', ['%matricula%'])
                ->whereNotIn('id', $cuotasPagandoIds)
                ->exists();
            if ($matriculaPendiente) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'cuotas_seleccionadas' => ['No se puede pagar Colegiatura sin Matrícula.']
                ]);
            }
        }

        if ($pagaCertificacion) {
            $colegiaturaPendiente = Cuota::where('inscripcione_id', $inscripcionId)
                ->where('pago_terminado', 'no')
                ->whereRaw('LOWER(nombre) LIKE ?', ['%colegiatura%'])
                ->whereNotIn('id', $cuotasPagandoIds)
                ->exists();
            if ($colegiaturaPendiente) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'cuotas_seleccionadas' => ['No se puede pagar Certificación sin Colegiatura.']
                ]);
            }
        }
    }

    private function crearPago($request)
    {
        return Pago::create([
            'recibo' => $this->generarRecibo(),
            'pago_bs' => $request->pago_bs,
            'descuento_bs' => $request->descuento_bs ?? 0,
            'fecha_pago' => $request->fecha_pago,
            'tipo_pago' => $request->tipo_pago,
        ])->id;
    }

    private function registrarDetallesParciales($request, $pagoId)
    {
        if ($request->tipo_pago === 'parcial') {
            if ($request->detalle_efectivo > 0) {
                Detalle::create([
                    'pago_id' => $pagoId,
                    'pago_bs' => $request->detalle_efectivo,
                    'tipo_pago' => 'efectivo'
                ]);
            }
            if ($request->detalle_qr > 0) {
                Detalle::create([
                    'pago_id' => $pagoId,
                    'pago_bs' => $request->detalle_qr,
                    'tipo_pago' => 'qr'
                ]);
            }
        }
    }

    private function aplicarPagoACuotas($cuotasData, $pagoId)
    {
        foreach ($cuotasData as $item) {
            $cuota = Cuota::find($item['cuota_id']);
            $monto = $item['monto'];

            if ($monto <= 0) continue;

            // Actualizar el saldo pendiente
            $cuota->pago_pendiente_bs = max(0, $cuota->pago_pendiente_bs - $monto);
            if ($cuota->pago_pendiente_bs <= 0) {
                $cuota->pago_terminado = 'si';
            }
            $cuota->save();

            // Registrar en pagos_cuotas
            PagosCuota::create([
                'cuota_id' => $cuota->id,
                'pago_id' => $pagoId,
                'pago_bs' => $monto
            ]);
        }
    }

    private function generarRecibo()
    {
        return \Haruncpi\LaravelIdGenerator\IdGenerator::generate([
            'table' => 'pagos',
            'field' => 'recibo',
            'length' => 10,
            'prefix' => 'REC-'
        ]);
    }


    public function cuotasPendientes(Inscripcione $inscripcion)
    {
        // Cargar cuotas pendientes (pago_terminado = 'no')
        $cuotas = $inscripcion->cuotas()
            ->where('pago_terminado', 'no')
            ->get();
        return response()->json($cuotas);
    }

    public function registrarConAsesor(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'oferta_id' => 'required|exists:ofertas_academicas,id',
            'asesor_id' => 'required|exists:trabajadores_cargos,id',
            'carnet' => 'required|string|max:20',
            'expedido' => 'nullable|string|max:10',
            'nombres' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'sexo' => 'nullable|string|in:Hombre,Mujer',
            'estado_civil' => 'nullable|string|max:50',
            'fecha_nacimiento' => 'nullable|date',
            'correo' => 'required|email|max:255',
            'celular' => 'required|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'ciudade_id' => 'required|exists:ciudades,id',
            'terminos' => 'required|accepted',
        ]);

        try {
            // 1. Verificar si la persona existe por carnet
            $persona = Persona::where('carnet', $request->carnet)->first();

            if (!$persona) {
                // La persona no existe, crear nueva persona
                $persona = Persona::create([
                    'carnet' => $request->carnet,
                    'expedido' => $request->expedido,
                    'nombres' => $request->nombres,
                    'apellido_paterno' => $request->apellido_paterno,
                    'apellido_materno' => $request->apellido_materno,
                    'sexo' => $request->sexo,
                    'estado_civil' => $request->estado_civil,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'correo' => $request->correo,
                    'celular' => $request->celular,
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion,
                    'ciudade_id' => $request->ciudade_id,
                ]);
            } else {
                // La persona existe, actualizar datos si es necesario
                $persona->update([
                    'nombres' => $request->nombres,
                    'apellido_paterno' => $request->apellido_paterno,
                    'apellido_materno' => $request->apellido_materno,
                    'correo' => $request->correo,
                    'celular' => $request->celular,
                    'ciudade_id' => $request->ciudade_id,
                ]);
            }

            // 2. Verificar si la persona existe como estudiante
            $estudiante = Estudiante::where('persona_id', $persona->id)->first();

            if (!$estudiante) {
                // La persona no es estudiante, crear nuevo estudiante
                $estudiante = Estudiante::create([
                    'persona_id' => $persona->id,
                ]);
            }

            // 3. Verificar si el estudiante ya está inscrito en la oferta académica
            $inscripcionExistente = Inscripcione::where('estudiante_id', $estudiante->id)
                ->where('ofertas_academica_id', $request->oferta_id)
                ->whereIn('estado', ['Pre-Inscrito', 'Inscrito'])
                ->first();

            if ($inscripcionExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya se tiene registro en el programa, el asesor se comunicará con usted.',
                    'estado' => $inscripcionExistente->estado,
                    'fecha_registro' => $inscripcionExistente->fecha_registro,
                ], 422);
            }

            // 4. Crear la inscripción con estado "Pre-Inscrito"
            $inscripcion = Inscripcione::create([
                'estudiante_id' => $estudiante->id,
                'ofertas_academica_id' => $request->oferta_id,
                'trabajadores_cargo_id' => $request->asesor_id,
                'estado' => 'Pre-Inscrito',
                'fecha_registro' => now(),
                'planes_pago_id' => null, // Para pre-inscripción no se asigna plan de pago
                'observacion' => 'Pre-inscripción realizada desde formulario público con asesor'
            ]);

            // Obtener datos para la respuesta
            $oferta = OfertasAcademica::with(['programa', 'sucursal'])->find($request->oferta_id);
            $asesor = TrabajadoresCargo::with(['trabajador.persona', 'cargo'])->find($request->asesor_id);

            return response()->json([
                'success' => true,
                'message' => 'Pre-inscripción registrada exitosamente. Nuestro asesor se pondrá en contacto contigo pronto.',
                'data' => [
                    'inscripcion_id' => $inscripcion->id,
                    'estudiante' => [
                        'nombre_completo' => trim($persona->nombres . ' ' . $persona->apellido_paterno . ' ' . $persona->apellido_materno),
                        'carnet' => $persona->carnet,
                        'celular' => $persona->celular,
                        'correo' => $persona->correo,
                    ],
                    'programa' => [
                        'nombre' => $oferta->programa->nombre ?? 'Programa no disponible',
                        'codigo' => $oferta->codigo,
                        'sucursal' => $oferta->sucursal->nombre ?? 'Sede no disponible',
                    ],
                    'asesor' => [
                        'nombre' => $asesor->trabajador->persona->nombres . ' ' . $asesor->trabajador->persona->apellido_paterno,
                        'cargo' => $asesor->cargo->nombre ?? 'Asesor',
                        'celular' => $asesor->trabajador->persona->celular ?? 'No disponible',
                    ],
                    'fecha_registro' => $inscripcion->fecha_registro->format('d/m/Y H:i'),
                    'estado' => $inscripcion->estado,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar errores de validación
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al registrar pre-inscripción con asesor: ' . $e->getMessage());
            \Log::error('Datos recibidos: ' . json_encode($request->all()));
            \Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la pre-inscripción. Por favor, inténtalo nuevamente o contacta con soporte.',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    // Método para convertir "Inscrito-Con-Adelanto" a "Inscrito"
    public function convertirAInscrito(Request $request, $inscripcionId)
    {
        $request->validate([
            'planes_pago_id' => 'required|exists:planes_pagos,id',
            'monto_minimo' => 'required|numeric|min:0',
        ]);

        $inscripcion = Inscripcione::findOrFail($inscripcionId);

        // Verificar que esté en estado "Inscrito-Con-Adelanto"
        if ($inscripcion->estado !== 'Inscrito-Con-Adelanto') {
            return response()->json([
                'success' => false,
                'msg' => 'Solo se pueden convertir inscripciones con adelanto.'
            ], 422);
        }

        $oferta = $inscripcion->ofertaAcademica;

        // Verificar que el plan pertenece a la oferta
        $planExisteEnOferta = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
            ->where('planes_pago_id', $request->planes_pago_id)
            ->exists();

        if (!$planExisteEnOferta) {
            return response()->json([
                'success' => false,
                'msg' => 'El plan de pago no pertenece a esta oferta.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Registrar el pago del monto mínimo
            $pagoMinimo = Pago::create([
                'recibo' => $this->generarRecibo(),
                'pago_bs' => $request->monto_minimo,
                'descuento_bs' => 0,
                'fecha_pago' => now(),
                'tipo_pago' => 'efectivo',
                'observacion' => 'Pago mínimo para formalizar inscripción',
            ]);

            // Cambiar estado a "Inscrito"
            $inscripcion->estado = 'Inscrito';
            $inscripcion->planes_pago_id = $request->planes_pago_id;
            $inscripcion->save();

            // Generar cuotas (similar a confirmarCuotas pero usando el adelanto)
            $planesConceptos = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
                ->where('planes_pago_id', $request->planes_pago_id)
                ->with('concepto')
                ->get();

            $adelantoRestante = $inscripcion->adelanto_bs ?? 0;

            foreach ($planesConceptos as $pc) {
                $montoTotal = $pc->pago_bs;
                $nCuotas = $pc->n_cuotas;
                $montoBase = floor($montoTotal / $nCuotas);
                $resto = $montoTotal - ($montoBase * $nCuotas);

                for ($i = 1; $i <= $nCuotas; $i++) {
                    $montoCuota = $i == $nCuotas ? $montoBase + $resto : $montoBase;
                    $fechaPago = now()->copy()->addMonths($i - 1)->format('Y-m-d');
                    $pagoPendiente = $montoCuota;

                    // Aplicar adelanto a la primera cuota
                    if ($i == 1 && $adelantoRestante > 0) {
                        $adelantoAplicado = min($adelantoRestante, $montoCuota);
                        $pagoPendiente = max(0, $montoCuota - $adelantoAplicado);
                        $adelantoRestante -= $adelantoAplicado;
                    }

                    $cuota = Cuota::create([
                        'nombre' => "Cuota {$i} - {$pc->concepto->nombre}",
                        'n_cuota' => $i,
                        'pago_total_bs' => $montoCuota,
                        'pago_pendiente_bs' => $pagoPendiente,
                        'descuento_bs' => 0,
                        'fecha_pago' => $fechaPago,
                        'pago_terminado' => $pagoPendiente <= 0 ? 'si' : 'no',
                        'inscripcione_id' => $inscripcion->id,
                    ]);

                    // Registrar pago del adelanto aplicado
                    if ($i == 1 && isset($adelantoAplicado) && $adelantoAplicado > 0) {
                        PagosCuota::create([
                            'cuota_id' => $cuota->id,
                            'pago_id' => $pagoMinimo->id, // O buscar el pago del adelanto original
                            'pago_bs' => $adelantoAplicado
                        ]);
                    }
                }
            }

            // Matricular en módulos
            foreach ($oferta->modulos as $modulo) {
                Matriculacione::firstOrCreate([
                    'inscripcione_id' => $inscripcion->id,
                    'modulo_id' => $modulo->id,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Inscripción formalizada correctamente. El estudiante ahora está inscrito.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'msg' => 'Error al formalizar la inscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cambiarPlanPago(Request $request)
    {
        $request->validate([
            'inscripcion_id' => 'required|exists:inscripciones,id',
            'nuevo_plan_pago_id' => 'required|exists:planes_pagos,id',
            'oferta_id' => 'required|exists:ofertas_academicas,id',
            'adelanto_bs' => 'nullable|numeric|min:0', // Validar adelanto
        ]);

        try {
            // Buscar la inscripción
            $inscripcion = Inscripcione::findOrFail($request->inscripcion_id);

            // Verificar que sea Pre-Inscrito
            if ($inscripcion->estado !== 'Pre-Inscrito') {
                return response()->json([
                    'success' => false,
                    'msg' => 'Solo se puede cambiar el plan de pago de pre-inscripciones.'
                ], 422);
            }

            // Verificar que la inscripción pertenezca a la oferta
            if ($inscripcion->ofertas_academica_id != $request->oferta_id) {
                return response()->json([
                    'success' => false,
                    'msg' => 'La inscripción no pertenece a esta oferta académica.'
                ], 422);
            }

            // Verificar que el nuevo plan pertenezca a la oferta
            $planExisteEnOferta = PlanesConcepto::where('ofertas_academica_id', $request->oferta_id)
                ->where('planes_pago_id', $request->nuevo_plan_pago_id)
                ->exists();

            if (!$planExisteEnOferta) {
                return response()->json([
                    'success' => false,
                    'msg' => 'El plan de pago no pertenece a esta oferta académica.'
                ], 422);
            }

            // Verificar si el estudiante ya tiene una inscripción con el nuevo plan
            $inscripcionExistente = Inscripcione::where('estudiante_id', $inscripcion->estudiante_id)
                ->where('ofertas_academica_id', $request->oferta_id)
                ->where('planes_pago_id', $request->nuevo_plan_pago_id)
                ->where('id', '!=', $inscripcion->id)
                ->exists();

            if ($inscripcionExistente) {
                return response()->json([
                    'success' => false,
                    'msg' => 'El estudiante ya tiene una inscripción con este plan de pago.'
                ], 422);
            }

            // Obtener el nuevo plan
            $nuevoPlan = PlanesPago::find($request->nuevo_plan_pago_id);

            // Actualizar el plan de pago y el adelanto
            $inscripcion->planes_pago_id = $request->nuevo_plan_pago_id;

            // Si se proporcionó un adelanto, actualizarlo
            if ($request->has('adelanto_bs') && $request->adelanto_bs > 0) {
                $inscripcion->adelanto_bs = $request->adelanto_bs;
            }

            $inscripcion->save();

            // Registrar en observaciones
            $observacionActual = $inscripcion->observacion ?? '';
            $adelantoMsg = $request->has('adelanto_bs') && $request->adelanto_bs > 0
                ? " | Adelanto registrado: " . number_format($request->adelanto_bs, 2) . " Bs"
                : "";

            $inscripcion->observacion = $observacionActual .
                " | Cambio de plan: " . now()->format('d/m/Y H:i') .
                " - Nuevo plan: " . $nuevoPlan->nombre . $adelantoMsg;
            $inscripcion->save();

            return response()->json([
                'success' => true,
                'msg' => 'Plan de pago ' . ($request->has('adelanto_bs') && $request->adelanto_bs > 0 ? 'y adelanto ' : '') . 'actualizado correctamente.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al cambiar plan de pago: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al cambiar el plan de pago: ' . $e->getMessage()
            ], 500);
        }
    }
}
