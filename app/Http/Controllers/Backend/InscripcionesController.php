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
        \Log::info('=== INICIANDO REGISTRO DE INSCRIPCIÓN ===');

        $user = Auth::user();
        \Log::info('Usuario autenticado:', [
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'persona_id' => $user?->persona_id,
        ]);

        if (!$user || !$user->persona) {
            \Log::warning('El usuario NO tiene una persona asociada.');
        } else {
            \Log::info('Persona encontrada:', [
                'persona_id' => $user->persona->id,
                'nombres' => $user->persona->nombres,
            ]);

            $trabajador = $user->persona->trabajador;
            if (!$trabajador) {
                \Log::warning('La persona NO tiene un trabajador asociado.');
            } else {
                \Log::info('Trabajador encontrado:', [
                    'trabajador_id' => $trabajador->id,
                ]);

                $cargosVigentes = $trabajador->trabajadores_cargos()->where('estado', 'Vigente')->get();
                \Log::info('Cargos vigentes del trabajador:', $cargosVigentes->toArray());

                if ($cargosVigentes->isEmpty()) {
                    \Log::warning('El trabajador NO tiene cargos vigentes.');
                }
            }
        }
        $request->validate([
            'oferta_id' => 'required|exists:ofertas_academicas,id',
            'estudiante_id' => 'required|exists:estudiantes,id',
            'estado' => 'required|in:Pre-Inscrito,Inscrito',
        ]);

        $oferta = OfertasAcademica::findOrFail($request->oferta_id);

        // ✅ NUEVA VALIDACIÓN: Verificar si ya está inscrito/pre-inscrito en esta oferta
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

        // Validación adicional solo para "Inscrito"
        if ($request->estado === 'Inscrito') {
            $request->validate([
                'planes_pago_id' => 'required|exists:planes_pagos,id',
            ]);

            $planExisteEnOferta = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
                ->where('planes_pago_id', $request->planes_pago_id)
                ->exists();

            if (!$planExisteEnOferta) {
                return response()->json([
                    'success' => false,
                    'msg' => 'El plan de pago seleccionado no pertenece a esta oferta académica.'
                ], 422);
            }
        }

        // ✅ Obtener el usuario autenticado correctamente
        $user = Auth::user();

        if (!$user || !$user->persona?->trabajador) {
            return response()->json([
                'success' => false,
                'msg' => 'El usuario actual no está asociado a un trabajador. Contacte al administrador.'
            ], 422);
        }


        $trabajadorCargo = $user->persona->trabajador->trabajadores_cargos()
            ->where('estado', 'Vigente')
            ->latest('fecha_ingreso')  // Recomendado: el cargo más reciente
            ->firstOrFail();

        if (!$trabajadorCargo) {
            return response()->json([
                'success' => false,
                'msg' => 'No tiene un cargo vigente asignado. No se puede registrar la inscripción.'
            ], 422);
        }

        // Registrar la inscripción
        $inscripcion = Inscripcione::create([
            'ofertas_academica_id' => $oferta->id,
            'estudiante_id' => $request->estudiante_id,
            'trabajadores_cargo_id' => $trabajadorCargo->id,
            'estado' => $request->estado,
            'fecha_registro' => now(),
            'planes_pago_id' => $request->estado === 'Inscrito' ? $request->planes_pago_id : null,
        ]);

        // Si es "Inscrito", devolver vista previa de cuotas
        if ($request->estado === 'Inscrito') {
            $planesConceptos = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
                ->where('planes_pago_id', $request->planes_pago_id)
                ->with('concepto')
                ->get();

            $cuotasPreview = [];
            foreach ($planesConceptos as $pc) {
                $montoTotal = $pc->pago_bs;
                $nCuotas = $pc->n_cuotas;
                $montoBase = floor($montoTotal / $nCuotas);
                $resto = $montoTotal - ($montoBase * $nCuotas);

                for ($i = 1; $i <= $nCuotas; $i++) {
                    $monto = $i == $nCuotas ? $montoBase + $resto : $montoBase;
                    $fechaPago = now()->copy()->addMonths($i - 1)->format('Y-m-d');

                    $cuotasPreview[] = [
                        'nombre' => "Cuota {$i} - {$pc->concepto->nombre}",
                        'n_cuota' => $i,
                        'pago_total_bs' => $monto,
                        'pago_pendiente_bs' => $monto,
                        'descuento_bs' => 0,
                        'fecha_pago' => $fechaPago,
                        'pago_terminado' => 'no',
                        'concepto_id' => $pc->concepto_id,
                        'concepto_nombre' => $pc->concepto->nombre,
                        'planes_concepto_id' => $pc->id,
                        'n_cuotas' => $nCuotas,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'msg' => 'Inscripción registrada como "Inscrito". Por favor, revise y confirme las cuotas.',
                'inscripcion_id' => $inscripcion->id,
                'cuotas_preview' => $cuotasPreview,
            ]);
        }

        // ✅ Respuesta para Pre-Inscrito
        return response()->json([
            'success' => true,
            'msg' => 'Inscripción registrada como "Pre-Inscrito" correctamente.'
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

        // Agrupar las cuotas por concepto_id
        $cuotasPorConcepto = collect($request->cuotas_data)->groupBy('concepto_id');

        // Verificar que cada concepto tenga la cantidad correcta de cuotas
        foreach ($cuotasPorConcepto as $conceptoId => $cuotas) {
            $planConcepto = PlanesConcepto::where('ofertas_academica_id', $oferta->id)
                ->where('planes_pago_id', $request->planes_pago_id)
                ->where('concepto_id', $conceptoId)
                ->first();

            if (!$planConcepto) {
                return response()->json([
                    'success' => false,
                    'msg' => "El concepto ID {$conceptoId} no está asociado al plan de pago en esta oferta."
                ], 422);
            }

            if ($cuotas->count() != $planConcepto->n_cuotas) {
                return response()->json([
                    'success' => false,
                    'msg' => "El concepto {$conceptoId} debe tener exactamente {$planConcepto->n_cuotas} cuotas, pero se enviaron {$cuotas->count()}."
                ], 422);
            }

            // Verificar que las n_cuota sean 1, 2, ..., N sin saltos ni duplicados
            $numerosCuota = $cuotas->pluck('n_cuota')->sort()->values();
            $esperado = collect(range(1, $planConcepto->n_cuotas));
            if ($numerosCuota->implode(',') !== $esperado->implode(',')) {
                return response()->json([
                    'success' => false,
                    'msg' => "Las cuotas del concepto {$conceptoId} deben ser secuenciales desde 1 hasta {$planConcepto->n_cuotas}."
                ], 422);
            }
        }

        // Crear la inscripción si no existe
        $inscripcion = Inscripcione::where([
            ['ofertas_academica_id', $oferta->id],
            ['estudiante_id', $request->estudiante_id],
            ['planes_pago_id', $request->planes_pago_id],
            ['estado', 'Inscrito']
        ])->first();

        if (!$inscripcion) {
            $user = Auth::user();

            $trabajadorCargo = $user->persona->trabajador->trabajadores_cargos()
                ->where('estado', 'Vigente')
                ->first();
            // Usar un trabajador cargo válido (puedes mejorar esta lógica)


            $inscripcion = Inscripcione::create([
                'ofertas_academica_id' => $oferta->id,
                'estudiante_id' => $request->estudiante_id,
                'trabajadores_cargo_id' => $trabajadorCargo->id,
                'estado' => 'Inscrito',
                'fecha_registro' => now(),
                'planes_pago_id' => $request->planes_pago_id,
            ]);
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
                'pago_terminado' => 'no', // 👈 CORREGIDO: string 'no', no boolean false
                'inscripcione_id' => $inscripcion->id,
            ]);
        }

        // Matricular en todos los módulos
        foreach ($oferta->modulos as $modulo) {
            Matriculacione::firstOrCreate([
                'inscripcione_id' => $inscripcion->id,
                'modulo_id' => $modulo->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Cuotas y matrículas registradas correctamente.'
        ]);
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
}
