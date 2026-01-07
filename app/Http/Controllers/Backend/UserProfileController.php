<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Trabajadore;
use App\Models\Estudio;
use App\Models\GradosAcademico;
use App\Models\Profesione;
use App\Models\Universidade;
use App\Models\Cargo;
use App\Models\Sucursale;
use App\Models\TrabajadoresCargo;
use App\Models\Inscripcione;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    /**
     * Mostrar vista del perfil
     */
    public function profile()
    {
        $user = Auth::user();
        $persona = $user->persona;

        // Cargar datos para formularios
        $grados = GradosAcademico::all();
        $profesiones = Profesione::all();
        $universidades = Universidade::all();

        // Cargos disponibles
        $cargos = Cargo::all();
        $sucursales = Sucursale::all();

        return view('admin.profile.index', compact(
            'persona',
            'grados',
            'profesiones',
            'universidades',
            'cargos',
            'sucursales'
        ));
    }

    /**
     * Obtener planes de pago de una oferta para el modal de conversión
     */
    public function obtenerPlanesPagoOferta($id)
    {
        try {
            $oferta = \App\Models\OfertasAcademica::with([
                'plan_concepto.plan_pago',
                'plan_concepto.concepto'
            ])->findOrFail($id);

            // Agrupar por plan de pago
            $planesAgrupados = [];

            foreach ($oferta->plan_concepto as $pc) {
                $planId = $pc->planes_pago_id;
                $planNombre = $pc->plan_pago->nombre ?? 'Sin nombre';

                if (!isset($planesAgrupados[$planId])) {
                    $planesAgrupados[$planId] = [
                        'id' => $planId,
                        'nombre' => $planNombre,
                        'conceptos' => []
                    ];
                }

                // Calcular el monto por cuota
                $montoPorCuota = $pc->n_cuotas > 0 ? $pc->pago_bs / $pc->n_cuotas : $pc->pago_bs;

                $planesAgrupados[$planId]['conceptos'][] = [
                    'concepto_id' => $pc->concepto_id,
                    'concepto_nombre' => $pc->concepto->nombre ?? 'Sin concepto',
                    'n_cuotas' => $pc->n_cuotas,
                    'pago_bs' => $pc->pago_bs,
                    'monto_por_cuota' => number_format($montoPorCuota, 2),
                    'total_concepto' => number_format($pc->pago_bs, 2)
                ];
            }

            return response()->json([
                'success' => true,
                'planes' => array_values($planesAgrupados),
                'oferta' => [
                    'id' => $oferta->id,
                    'codigo' => $oferta->codigo,
                    'programa_nombre' => $oferta->programa->nombre ?? 'Sin programa'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener planes de pago: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los planes de pago'
            ], 500);
        }
    }

    /**
     * Convertir pre-inscrito a inscrito
     */
    public function convertirPreInscritoAInscrito(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'inscripcion_id' => 'required|exists:inscripciones,id',
                'plan_pago_id' => 'required|exists:planes_pagos,id',
                'observacion' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = Auth::user();
            $persona = $user->persona;

            if (!$persona->trabajador) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para realizar esta acción'
                ], 403);
            }

            // Obtener la inscripción con todas las relaciones necesarias
            $inscripcion = \App\Models\Inscripcione::with([
                'ofertaAcademica.modulos',
                'estudiante',
                'ofertaAcademica.plan_concepto' => function ($query) use ($request) {
                    $query->where('planes_pago_id', $request->plan_pago_id);
                }
            ])->findOrFail($request->inscripcion_id);

            // Verificar que esté en estado Pre-Inscrito
            if ($inscripcion->estado !== 'Pre-Inscrito') {
                return response()->json([
                    'success' => false,
                    'message' => 'La inscripción no está en estado Pre-Inscrito'
                ], 422);
            }

            // Verificar que el trabajador tenga permisos sobre esta inscripción
            $cargosMarketingIds = $persona->trabajador->trabajadores_cargos()
                ->whereIn('cargo_id', [2, 3, 6])
                ->where('estado', 'Vigente')
                ->pluck('id');

            if (!$cargosMarketingIds->contains($inscripcion->trabajadores_cargo_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para modificar esta inscripción'
                ], 403);
            }

            // Iniciar transacción para asegurar que todo se guarde correctamente
            \DB::beginTransaction();

            try {
                // Actualizar la inscripción
                $inscripcion->estado = 'Inscrito';
                $inscripcion->planes_pago_id = $request->plan_pago_id;
                $inscripcion->observacion = $request->observacion ?? 'Convertido desde Pre-Inscrito por ' . $persona->nombres;
                $inscripcion->save();

                // 1. Generar matriculaciones para cada módulo
                $this->generarMatriculaciones($inscripcion);

                // 2. Generar cuotas basadas en el plan de pago seleccionado
                $this->generarCuotasInscripcion($inscripcion, $request->plan_pago_id);

                \DB::commit();

                \Log::info("Pre-inscripción {$inscripcion->id} convertida a inscrito por usuario {$user->id}");

                return response()->json([
                    'success' => true,
                    'message' => 'Pre-inscripción convertida a inscrita exitosamente. Se han generado las matriculaciones y cuotas correspondientes.',
                    'inscripcion_id' => $inscripcion->id
                ]);
            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            \Log::error('Error al convertir pre-inscrito: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar matriculaciones para cada módulo de la oferta académica
     */
    private function generarMatriculaciones($inscripcion)
    {
        try {
            $oferta = $inscripcion->ofertaAcademica;

            if (!$oferta) {
                throw new \Exception('No se encontró la oferta académica asociada a la inscripción');
            }

            // Cargar módulos de la oferta
            $modulos = $oferta->modulos;

            if ($modulos->isEmpty()) {
                throw new \Exception('La oferta académica no tiene módulos configurados');
            }

            foreach ($modulos as $modulo) {
                // Verificar si ya existe una matriculación para este módulo e inscripción
                $existeMatriculacion = \App\Models\Matriculacione::where('inscripcione_id', $inscripcion->id)
                    ->where('modulo_id', $modulo->id)
                    ->exists();

                if (!$existeMatriculacion) {
                    \App\Models\Matriculacione::create([
                        'inscripcione_id' => $inscripcion->id,
                        'modulo_id' => $modulo->id,
                        'nota_regular' => 0,
                        'nota_nivelacion' => 0,
                    ]);
                }
            }

            \Log::info("Matriculaciones generadas para inscripción {$inscripcion->id}: " . $modulos->count() . " módulos");
            return true;
        } catch (\Exception $e) {
            \Log::error('Error al generar matriculaciones: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generar cuotas para una inscripción según el plan de pago seleccionado
     */
    /**
     * Generar cuotas para una inscripción según el plan de pago seleccionado
     * Sin decimales: la última cuota absorbe la diferencia
     */
    /**
     * Generar cuotas para una inscripción según el plan de pago seleccionado
     * Sin decimales: la última cuota absorbe la diferencia
     */
    private function generarCuotasInscripcion($inscripcion, $planPagoId)
    {
        try {
            // Obtener los conceptos del plan de pago para esta oferta
            $planConceptos = \App\Models\PlanesConcepto::where('ofertas_academica_id', $inscripcion->ofertas_academica_id)
                ->where('planes_pago_id', $planPagoId)
                ->with('concepto')
                ->get();

            if ($planConceptos->isEmpty()) {
                throw new \Exception('No se encontraron conceptos para el plan de pago seleccionado');
            }

            $totalCuotasGeneradas = 0;

            foreach ($planConceptos as $planConcepto) {
                // Obtener el monto total del concepto (sin decimales)
                $montoTotal = $this->redondearMontoSinDecimales($planConcepto->pago_bs);
                $nCuotas = (int) $planConcepto->n_cuotas;

                if ($nCuotas <= 0) {
                    $nCuotas = 1; // Si no hay cuotas especificadas, crear una sola
                }

                if ($nCuotas == 1) {
                    // Solo una cuota
                    $fechaPago = $this->calcularFechaCuota(1, $inscripcion->fecha_registro);

                    \App\Models\Cuota::create([
                        'nombre' => $planConcepto->concepto->nombre . ' - Única cuota',
                        'n_cuota' => 1,
                        'pago_total_bs' => $montoTotal,
                        'pago_pendiente_bs' => $montoTotal,
                        'descuento_bs' => 0,
                        'fecha_pago' => $fechaPago,
                        'pago_terminado' => 'no',
                        'inscripcione_id' => $inscripcion->id,
                    ]);

                    $totalCuotasGeneradas++;

                    \Log::info("Única cuota generada: {$montoTotal} Bs para concepto {$planConcepto->concepto->nombre}");
                } else {
                    // Múltiples cuotas
                    // Calcular el monto base por cuota (sin decimales)
                    $montoBasePorCuota = (int) floor($montoTotal / $nCuotas);

                    // Calcular la diferencia que se agregará a la última cuota
                    $diferencia = $montoTotal - ($montoBasePorCuota * $nCuotas);

                    \Log::info("Generando cuotas para concepto: {$planConcepto->concepto->nombre}");
                    \Log::info("Monto total: {$montoTotal}, Cuotas: {$nCuotas}");
                    \Log::info("Monto base por cuota: {$montoBasePorCuota}, Diferencia: {$diferencia}");

                    // Crear cuotas para este concepto
                    for ($i = 1; $i <= $nCuotas; $i++) {
                        // Para la última cuota, agregar la diferencia
                        $montoCuota = ($i === $nCuotas)
                            ? $montoBasePorCuota + $diferencia
                            : $montoBasePorCuota;

                        // Calcular fecha de pago
                        $fechaPago = $this->calcularFechaCuota($i, $inscripcion->fecha_registro);

                        \App\Models\Cuota::create([
                            'nombre' => $planConcepto->concepto->nombre . ' - Cuota ' . $i . '/' . $nCuotas,
                            'n_cuota' => $i,
                            'pago_total_bs' => $montoCuota,
                            'pago_pendiente_bs' => $montoCuota,
                            'descuento_bs' => 0,
                            'fecha_pago' => $fechaPago,
                            'pago_terminado' => 'no',
                            'inscripcione_id' => $inscripcion->id,
                        ]);

                        $totalCuotasGeneradas++;

                        \Log::info("Cuota {$i} generada: {$montoCuota} Bs");
                    }

                    // Verificar que la suma de las cuotas sea igual al monto total
                    $sumaCuotas = $montoBasePorCuota * ($nCuotas - 1) + ($montoBasePorCuota + $diferencia);
                    \Log::info("Suma total de cuotas: {$sumaCuotas}, Monto total concepto: {$montoTotal}");

                    if ($sumaCuotas !== $montoTotal) {
                        \Log::warning("La suma de las cuotas ({$sumaCuotas}) no coincide exactamente con el monto total ({$montoTotal})");
                    }
                }
            }

            \Log::info("Cuotas generadas para inscripción {$inscripcion->id}: " . $totalCuotasGeneradas . " cuotas");
            return true;
        } catch (\Exception $e) {
            \Log::error('Error al generar cuotas: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Método auxiliar para manejar montos sin decimales
     */
    private function redondearMontoSinDecimales($monto)
    {
        // Si el monto tiene decimales, redondear hacia abajo
        if (is_float($monto)) {
            return (int) floor($monto);
        }

        // Si es string con decimales, convertir
        if (is_string($monto) && strpos($monto, '.') !== false) {
            return (int) floor((float) $monto);
        }

        return (int) $monto;
    }

    /**
     * Generar formulario PDF de inscripción
     */
    public function generarFormularioPdf($id)
    {
        try {
            // Obtener la inscripción con todas las relaciones necesarias
            $inscripcion = \App\Models\Inscripcione::with([
                'ofertaAcademica.programa',
                'ofertaAcademica.sucursal.sede',
                'ofertaAcademica.modulos',
                'ofertaAcademica.plan_concepto.plan_pago',
                'ofertaAcademica.plan_concepto.concepto',
                'ofertaAcademica.posgrado.area',
                'ofertaAcademica.posgrado.tipo',
                'ofertaAcademica.modalidad',
                'ofertaAcademica.fase',
                'estudiante.persona.ciudad.departamento',
                'estudiante.persona.estudios.grado_academico',
                'estudiante.persona.estudios.profesion',
                'estudiante.persona.estudios.universidad',
                'planesPago',
                'trabajador_cargo.trabajador.persona',
                'trabajador_cargo.cargo',
                'matriculaciones.modulo',
                'cuotas'
            ])->findOrFail($id);

            // Verificar permisos del usuario
            $user = Auth::user();
            $persona = $user->persona;

            if (!$persona->trabajador) {
                abort(403, 'No tiene permisos para ver esta inscripción');
            }

            $cargosMarketingIds = $persona->trabajador->trabajadores_cargos()
                ->whereIn('cargo_id', [2, 3, 6])
                ->where('estado', 'Vigente')
                ->pluck('id');

            if (!$cargosMarketingIds->contains($inscripcion->trabajadores_cargo_id)) {
                abort(403, 'No tiene permisos para ver esta inscripción');
            }

            // Calcular fechas de pago para cada concepto
            $planesConFechas = $this->calcularFechasPagoPlanes($inscripcion);

            // Preparar datos para la vista
            $data = [
                'inscripcion' => $inscripcion,
                'planesConFechas' => $planesConFechas,
                'fecha_actual' => now()->format('d/m/Y'),
                'hora_actual' => now()->format('H:i'),
                'logo_path' => public_path('frontend/assets/img/logo.png'), // Actualizado
                'has_logo' => file_exists(public_path('frontend/assets/img/logo.png')),
            ];

            // Generar PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.profile.pdf.formulario-inscripcion', $data);

            // Configurar el PDF
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'chroot' => public_path(),
            ]);

            // Nombre del archivo
            $filename = 'formulario_inscripcion_' . $inscripcion->estudiante->persona->carnet . '_' . now()->format('Ymd_His') . '.pdf';

            // Descargar el PDF
            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('Error al generar formulario PDF: ' . $e->getMessage());
            abort(500, 'Error al generar el formulario: ' . $e->getMessage());
        }
    }

    /**
     * Calcular fechas de pago para todos los planes
     */
    private function calcularFechasPagoPlanes($inscripcion)
    {
        $planesConFechas = [];

        // Obtener el plan de pago específico de la inscripción
        if ($inscripcion->planes_pago_id) {
            $planPago = \App\Models\PlanesPago::find($inscripcion->planes_pago_id);
            $planNombre = $planPago->nombre ?? 'Plan de Pago';

            // Obtener conceptos específicos del plan seleccionado
            $conceptos = \App\Models\PlanesConcepto::where('ofertas_academica_id', $inscripcion->ofertas_academica_id)
                ->where('planes_pago_id', $inscripcion->planes_pago_id)
                ->with('concepto')
                ->get();

            $planConFechas = [
                'id' => $inscripcion->planes_pago_id,
                'nombre' => $planNombre,
                'conceptos' => []
            ];

            foreach ($conceptos as $concepto) {
                $fechasPago = [];
                $nCuotas = (int) $concepto->n_cuotas;

                // Solo mostrar las primeras 6 fechas si hay muchas cuotas
                $maxFechasAMostrar = min($nCuotas, 6);

                for ($i = 1; $i <= $maxFechasAMostrar; $i++) {
                    $fechasPago[] = $this->calcularFechaCuota($i, $inscripcion->fecha_registro);
                }

                // Si hay más de 6 cuotas, agregar indicador
                if ($nCuotas > 6) {
                    $fechasPago[] = "... y " . ($nCuotas - 6) . " cuotas más";
                }

                $planConFechas['conceptos'][] = [
                    'concepto_id' => $concepto->concepto_id,
                    'nombre' => $concepto->concepto->nombre ?? 'Sin concepto',
                    'n_cuotas' => $nCuotas,
                    'monto_total' => (int) $concepto->pago_bs,
                    'fechas_pago' => $fechasPago,
                    'montos_cuotas' => $this->calcularMontosCuotas((int) $concepto->pago_bs, $nCuotas)
                ];
            }

            $planesConFechas[$inscripcion->planes_pago_id] = $planConFechas;
        }

        return $planesConFechas;
    }

    /**
     * Calcular montos de cuotas sin decimales
     */
    private function calcularMontosCuotas($montoTotal, $nCuotas)
    {
        if ($nCuotas <= 0) return [$montoTotal];

        if ($nCuotas == 1) return [$montoTotal];

        $montoBase = (int) floor($montoTotal / $nCuotas);
        $diferencia = $montoTotal - ($montoBase * $nCuotas);

        $montos = [];
        for ($i = 1; $i <= $nCuotas; $i++) {
            $montos[] = ($i === $nCuotas) ? $montoBase + $diferencia : $montoBase;
        }

        return $montos;
    }


    /**
     * Calcular fecha de pago para una cuota
     */
    private function calcularFechaCuota($numeroCuota, $fechaInicio)
    {
        try {
            $fecha = \Carbon\Carbon::parse($fechaInicio);

            // Primera cuota: hoy o en la fecha de inscripción
            if ($numeroCuota == 1) {
                return now()->format('Y-m-d');
            }

            // Cuotas posteriores: cada mes a partir de hoy
            // Si hoy es día 31 y el próximo mes tiene menos días, se ajusta al último día del mes
            $fechaPago = now()->addMonths($numeroCuota - 1);

            // Si la fecha original era día 31 y el nuevo mes no tiene día 31, usar el último día del mes
            $diaOriginal = $fecha->day;
            if ($diaOriginal > 28) {
                $ultimoDiaMes = $fechaPago->copy()->endOfMonth()->day;
                if ($diaOriginal > $ultimoDiaMes) {
                    $fechaPago = $fechaPago->endOfMonth();
                } else {
                    $fechaPago->day = $diaOriginal;
                }
            }

            return $fechaPago->format('Y-m-d');
        } catch (\Exception $e) {
            \Log::error('Error al calcular fecha de cuota: ' . $e->getMessage());
            // Fallback: si hay error, usar fecha actual más meses
            $fechaActual = now();
            if ($numeroCuota == 1) {
                return $fechaActual->format('Y-m-d');
            }
            return $fechaActual->addMonths($numeroCuota - 1)->format('Y-m-d');
        }
    }

    /**
     * Obtener datos del perfil para AJAX
     */
    public function getProfileData()
    {
        $user = Auth::user();
        $persona = $user->persona;

        if (!$persona) {
            return response()->json([
                'success' => false,
                'message' => 'Persona no encontrada'
            ], 404);
        }

        // Cargar relaciones
        $persona->load([
            'ciudad.departamento',
            'estudios.grado_academico',
            'estudios.profesion',
            'estudios.universidad',
            'trabajador.trabajadores_cargos.cargo',
            'trabajador.trabajadores_cargos.sucursal'
        ]);

        // Preparar datos
        $data = [
            'persona' => $persona,
            'tiene_trabajador' => $persona->trabajador ? true : false,
            'tiene_cargos_marketing' => false,
            'cargos_marketing' => []
        ];

        // Verificar si tiene cargos de marketing (2,3,6)
        if ($persona->trabajador) {
            $cargosMarketing = $persona->trabajador->trabajadores_cargos()
                ->whereIn('cargo_id', [2, 3, 6])
                ->where('estado', 'Vigente')
                ->get();

            if ($cargosMarketing->count() > 0) {
                $data['tiene_cargos_marketing'] = true;
                $data['cargos_marketing'] = $cargosMarketing;
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Actualizar datos personales
     */
    public function updatePersonal(Request $request)
    {
        $user = Auth::user();
        $persona = $user->persona;

        $validator = Validator::make($request->all(), [
            'nombres' => 'required|string|max:255',
            'apellido_paterno' => 'nullable|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'sexo' => 'required|in:Hombre,Mujer',
            'estado_civil' => 'required|in:Soltero(a),Casado(a),Divorciado(a),Viudo(a)',
            'fecha_nacimiento' => 'nullable|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'correo' => 'required|email|unique:personas,correo,' . $persona->id,
            'celular' => 'required|numeric',
            'telefono' => 'nullable|numeric',
            'ciudade_id' => 'nullable|exists:ciudades,id',
            'direccion' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Actualizar persona
        $persona->update($request->only([
            'nombres',
            'apellido_paterno',
            'apellido_materno',
            'sexo',
            'estado_civil',
            'fecha_nacimiento',
            'correo',
            'direccion',
            'celular',
            'telefono',
            'ciudade_id'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Datos personales actualizados correctamente',
            'persona' => $persona
        ]);
    }

    /**
     * Subir fotografía
     */
    /**
     * Subir fotografía
     */
    public function uploadFoto(Request $request)
    {
        $user = Auth::user();
        $persona = $user->persona;

        $validator = Validator::make($request->all(), [
            'fotografia' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB máximo
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear directorio si no existe
        $uploadPath = public_path('upload/personas');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Eliminar foto anterior si existe
        if ($persona->fotografia) {
            // Extraer solo el nombre del archivo de la ruta completa
            $oldFilename = basename($persona->fotografia);
            if (file_exists(public_path('upload/personas/' . $oldFilename))) {
                unlink(public_path('upload/personas/' . $oldFilename));
            }
        }

        // Guardar nueva foto
        $file = $request->file('fotografia');

        // Generar nombre único para la imagen
        $filename = 'persona_' . $persona->id . '_' . time() . '.' . $file->getClientOriginalExtension();

        // Mover el archivo
        $file->move(public_path('upload/personas'), $filename);

        // Guardar la ruta completa desde 'upload' en la base de datos
        $fotoPath = 'upload/personas/' . $filename;

        $persona->fotografia = $fotoPath;
        $persona->save();

        return response()->json([
            'success' => true,
            'message' => 'Fotografía actualizada correctamente',
            'foto_url' => asset($fotoPath) // asset() genera la URL completa
        ]);
    }

    /**
     * Obtener estudios de la persona
     */
    public function getEstudios()
    {
        $user = Auth::user();
        $persona = $user->persona;

        $estudios = $persona->estudios()
            ->with(['grado_academico', 'profesion', 'universidad'])
            ->get();

        return response()->json([
            'success' => true,
            'estudios' => $estudios
        ]);
    }

    /**
     * Actualizar estudios
     */
    public function updateEstudios(Request $request)
    {
        $user = Auth::user();
        $persona = $user->persona;

        $validator = Validator::make($request->all(), [
            'estudios' => 'required|array',
            'estudios.*.id' => 'nullable|exists:estudios,id',
            'estudios.*.grados_academico_id' => 'required|exists:grados_academicos,id',
            'estudios.*.profesione_id' => 'required|exists:profesiones,id',
            'estudios.*.universidade_id' => 'required|exists:universidades,id',
            'estudios.*.estado' => 'required|in:Concluido,En curso,Abandonado',
            'estudios.*.documento' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $estudiosIds = [];

        foreach ($request->estudios as $estudioData) {
            if (isset($estudioData['id']) && $estudioData['id']) {
                // Actualizar estudio existente
                $estudio = Estudio::find($estudioData['id']);
                if ($estudio && $estudio->persona_id == $persona->id) {
                    $estudio->update([
                        'grados_academico_id' => $estudioData['grados_academico_id'],
                        'profesione_id' => $estudioData['profesione_id'],
                        'universidade_id' => $estudioData['universidade_id'],
                        'estado' => $estudioData['estado'],
                        'documento' => $estudioData['documento'] ?? null,
                    ]);
                    $estudiosIds[] = $estudio->id;
                }
            } else {
                // Crear nuevo estudio
                $estudio = $persona->estudios()->create([
                    'grados_academico_id' => $estudioData['grados_academico_id'],
                    'profesione_id' => $estudioData['profesione_id'],
                    'universidade_id' => $estudioData['universidade_id'],
                    'estado' => $estudioData['estado'],
                    'documento' => $estudioData['documento'] ?? null,
                ]);
                $estudiosIds[] = $estudio->id;
            }
        }

        // Eliminar estudios que no están en la lista
        $persona->estudios()->whereNotIn('id', $estudiosIds)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Estudios actualizados correctamente'
        ]);
    }

    /**
     * Obtener cargos del trabajador
     */
    public function getCargos()
    {
        $user = Auth::user();
        $persona = $user->persona;

        if (!$persona->trabajador) {
            return response()->json([
                'success' => false,
                'message' => 'No es trabajador'
            ], 404);
        }

        $cargos = $persona->trabajador->trabajadores_cargos()
            ->with(['cargo', 'sucursal'])
            ->orderBy('principal', 'desc')
            ->orderBy('estado', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'cargos' => $cargos
        ]);
    }

    /**
     * Actualizar datos de un cargo
     */
    public function updateCargoData(Request $request)
    {
        $user = Auth::user();
        $persona = $user->persona;

        if (!$persona->trabajador) {
            return response()->json([
                'success' => false,
                'message' => 'No es trabajador'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'trabajadores_cargo_id' => 'required|exists:trabajadores_cargos,id',
            'sucursale_id' => 'nullable|exists:sucursales,id',
            'cargo_id' => 'nullable|exists:cargos,id',
            'fecha_ingreso' => 'nullable|date',
            'fecha_termino' => 'nullable|date|after_or_equal:fecha_ingreso',
            'observaciones' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $trabajadorCargo = TrabajadoresCargo::find($request->trabajadores_cargo_id);

        // Verificar que el cargo pertenezca al trabajador
        if ($trabajadorCargo->trabajadore_id != $persona->trabajador->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene permiso para modificar este cargo'
            ], 403);
        }

        // Actualizar datos
        $trabajadorCargo->update([
            'sucursale_id' => $request->sucursale_id ?? $trabajadorCargo->sucursale_id,
            'cargo_id' => $request->cargo_id ?? $trabajadorCargo->cargo_id,
            'fecha_ingreso' => $request->fecha_ingreso ?? $trabajadorCargo->fecha_ingreso,
            'fecha_termino' => $request->fecha_termino ?? $trabajadorCargo->fecha_termino,
            'observaciones' => $request->observaciones ?? $trabajadorCargo->observaciones,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Datos del cargo actualizados correctamente',
            'cargo' => $trabajadorCargo->load(['cargo', 'sucursal'])
        ]);
    }

    /**
     * Obtener inscripciones para marketing (cargos 2,3,6)
     */
    public function getInscripcionesMarketing(Request $request)
    {
        $user = Auth::user();
        $persona = $user->persona;

        if (!$persona->trabajador) {
            return response()->json([
                'success' => false,
                'message' => 'No es trabajador'
            ], 404);
        }

        // Obtener IDs de cargos de marketing
        $trabajadoresCargosIds = $persona->trabajador->trabajadores_cargos()
            ->whereIn('cargo_id', [2, 3, 6])
            ->where('estado', 'Vigente')
            ->pluck('id');

        if ($trabajadoresCargosIds->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene cargos de marketing asignados'
            ], 404);
        }

        $inscripciones = Inscripcione::with([
            'ofertaAcademica.programa',
            'ofertaAcademica.sucursal',
            'ofertaAcademica.posgrado.tipo',
            'estudiante.persona',
            'planesPago'
        ])
            ->whereIn('trabajadores_cargo_id', $trabajadoresCargosIds)
            ->whereIn('estado', ['Inscrito', 'Pre-Inscrito'])
            ->orderBy('fecha_registro', 'desc')
            ->paginate(10);

        // Estadísticas
        $totalInscripciones = $inscripciones->total();
        $totalInscritos = $inscripciones->where('estado', 'Inscrito')->count();
        $totalPreInscritos = $inscripciones->where('estado', 'Pre-Inscrito')->count();

        return response()->json([
            'success' => true,
            'inscripciones' => $inscripciones,
            'estadisticas' => [
                'total' => $totalInscripciones,
                'inscritos' => $totalInscritos,
                'pre_inscritos' => $totalPreInscritos
            ]
        ]);
    }

    /**
     * Obtener estadísticas de marketing para gráficos
     */
    public function getEstadisticasMarketing(Request $request)
    {
        $user = Auth::user();
        $persona = $user->persona;

        if (!$persona->trabajador) {
            return response()->json(['error' => 'No es trabajador'], 403);
        }

        // Obtener IDs de cargos de marketing
        $cargosMarketingIds = $persona->trabajador->trabajadores_cargos()
            ->whereIn('cargo_id', [2, 3, 6])
            ->where('estado', 'Vigente')
            ->pluck('id');

        if ($cargosMarketingIds->isEmpty()) {
            return response()->json(['error' => 'No tiene cargos de marketing'], 403);
        }

        // Parámetros de filtro
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', null);
        $programaId = $request->input('programa_id', null);

        // Asegurar que year sea un entero
        $year = (int)$year;

        // Consulta base
        $query = Inscripcione::whereIn('trabajadores_cargo_id', $cargosMarketingIds)
            ->whereIn('estado', ['Inscrito', 'Pre-Inscrito'])
            ->whereYear('fecha_registro', $year);

        if ($month && $month !== 'todos') {
            $query->whereMonth('fecha_registro', (int)$month);
        }

        if ($programaId) {
            $query->whereHas('ofertaAcademica', function ($q) use ($programaId) {
                $q->where('programa_id', $programaId);
            });
        }

        // Estadísticas por mes (para el año seleccionado)
        $estadisticasPorMes = Inscripcione::selectRaw("
        YEAR(fecha_registro) as year,
        MONTH(fecha_registro) as month,
        DATE_FORMAT(fecha_registro, '%Y-%m') as mes_key,
        DATE_FORMAT(fecha_registro, '%b') as mes_nombre,
        SUM(CASE WHEN estado = 'Inscrito' THEN 1 ELSE 0 END) as inscritos,
        SUM(CASE WHEN estado = 'Pre-Inscrito' THEN 1 ELSE 0 END) as pre_inscritos,
        COUNT(*) as total
    ")
            ->whereIn('trabajadores_cargo_id', $cargosMarketingIds)
            ->whereIn('estado', ['Inscrito', 'Pre-Inscrito'])
            ->whereYear('fecha_registro', $year)
            ->groupBy('year', 'month', 'mes_key', 'mes_nombre')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy('mes_key');

        // Construir datos para gráfico
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $datosGrafico = [
            'meses' => [],
            'inscritos' => [],
            'pre_inscritos' => [],
            'totales' => []
        ];

        for ($m = 1; $m <= 12; $m++) {
            $mesKey = $year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
            $datosGrafico['meses'][] = $meses[$m - 1];

            if (isset($estadisticasPorMes[$mesKey])) {
                $datosGrafico['inscritos'][] = (int)$estadisticasPorMes[$mesKey]->inscritos;
                $datosGrafico['pre_inscritos'][] = (int)$estadisticasPorMes[$mesKey]->pre_inscritos;
                $datosGrafico['totales'][] = (int)$estadisticasPorMes[$mesKey]->total;
            } else {
                $datosGrafico['inscritos'][] = 0;
                $datosGrafico['pre_inscritos'][] = 0;
                $datosGrafico['totales'][] = 0;
            }
        }

        // Estadísticas por programa
        $estadisticasPorPrograma = Inscripcione::selectRaw("
        programas.nombre as programa_nombre,
        COUNT(*) as total,
        SUM(CASE WHEN inscripciones.estado = 'Inscrito' THEN 1 ELSE 0 END) as inscritos,
        SUM(CASE WHEN inscripciones.estado = 'Pre-Inscrito' THEN 1 ELSE 0 END) as pre_inscritos
    ")
            ->join('ofertas_academicas', 'inscripciones.ofertas_academica_id', '=', 'ofertas_academicas.id')
            ->join('programas', 'ofertas_academicas.programa_id', '=', 'programas.id')
            ->whereIn('inscripciones.trabajadores_cargo_id', $cargosMarketingIds)
            ->whereIn('inscripciones.estado', ['Inscrito', 'Pre-Inscrito'])
            ->whereYear('inscripciones.fecha_registro', $year);

        if ($month && $month !== 'todos') {
            $estadisticasPorPrograma->whereMonth('inscripciones.fecha_registro', (int)$month);
        }

        $estadisticasPorPrograma = $estadisticasPorPrograma
            ->groupBy('programas.nombre')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Resumen general
        $resumen = [
            'total' => $query->count(),
            'inscritos' => $query->where('estado', 'Inscrito')->count(),
            'pre_inscritos' => $query->where('estado', 'Pre-Inscrito')->count(),
            'mes_seleccionado' => $month ? $meses[(int)$month - 1] : 'Todos los meses',
            'anio_seleccionado' => $year
        ];

        return response()->json([
            'success' => true,
            'grafico' => $datosGrafico,
            'programas' => $estadisticasPorPrograma,
            'resumen' => $resumen
        ]);
    }

    /**
     * Obtener inscripciones filtradas
     */
    public function getInscripcionesFiltradas(Request $request)
    {
        $user = Auth::user();
        $persona = $user->persona;

        if (!$persona->trabajador) {
            return response()->json(['error' => 'No es trabajador'], 403);
        }

        // Obtener IDs de cargos de marketing
        $cargosMarketingIds = $persona->trabajador->trabajadores_cargos()
            ->whereIn('cargo_id', [2, 3, 6])
            ->where('estado', 'Vigente')
            ->pluck('id');

        if ($cargosMarketingIds->isEmpty()) {
            return response()->json(['error' => 'No tiene cargos de marketing'], 403);
        }

        // Parámetros de filtro
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', null);
        $programaId = $request->input('programa_id', null);
        $estado = $request->input('estado', null);
        $search = $request->input('search', '');

        // Consulta
        $query = Inscripcione::whereIn('trabajadores_cargo_id', $cargosMarketingIds)
            ->whereIn('estado', ['Inscrito', 'Pre-Inscrito'])
            ->whereYear('fecha_registro', $year)
            ->with([
                'ofertaAcademica.programa',
                'ofertaAcademica.sucursal.sede',
                'estudiante.persona',
                'planesPago'
            ]);

        if ($month && $month !== 'todos') {
            $query->whereMonth('fecha_registro', $month);
        }

        if ($programaId) {
            $query->whereHas('ofertaAcademica', function ($q) use ($programaId) {
                $q->where('programa_id', $programaId);
            });
        }

        if ($estado && in_array($estado, ['Inscrito', 'Pre-Inscrito'])) {
            $query->where('estado', $estado);
        }

        if ($search) {
            $query->whereHas('estudiante.persona', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                    ->orWhere('apellido_paterno', 'like', "%{$search}%")
                    ->orWhere('apellido_materno', 'like', "%{$search}%")
                    ->orWhere('carnet', 'like', "%{$search}%");
            });
        }

        $inscripciones = $query->orderBy('fecha_registro', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'inscripciones' => $inscripciones,
            'pagination' => [
                'current_page' => $inscripciones->currentPage(),
                'last_page' => $inscripciones->lastPage(),
                'total' => $inscripciones->total(),
                'per_page' => $inscripciones->perPage()
            ]
        ]);
    }

    /**
     * Obtener ofertas académicas en fase 3 para marketing
     */
    /**
     * Obtener ofertas académicas en fase 3 para marketing
     */
    public function getOfertasMarketingActivas(Request $request)
    {
        try {
            \Log::info('=== INICIANDO getOfertasMarketingActivas ===');

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            if (!$user->persona) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no tiene persona asociada'
                ], 403);
            }

            $persona = $user->persona;

            if (!$persona->trabajador) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene un trabajador asociado'
                ], 404);
            }

            // Obtener el cargo principal del trabajador
            $cargoPrincipal = $persona->trabajador->trabajadores_cargos()
                ->where('principal', 1)
                ->where('estado', 'Vigente')
                ->with(['cargo', 'sucursal'])
                ->first();

            if (!$cargoPrincipal) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene un cargo principal vigente'
                ], 404);
            }

            // Obtener ofertas académicas en fase de Inscripciones
            $faseInscripciones = \App\Models\Fase::where('nombre', 'Inscripciones')->first();

            if (!$faseInscripciones) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró la fase "Inscripciones" en el sistema'
                ], 404);
            }

            // Consulta de ofertas
            $query = \App\Models\OfertasAcademica::with([
                'programa',
                'sucursal.sede',
                'modalidad',
                'fase'
            ])
                ->where('fase_id', $faseInscripciones->id)
                ->orderBy('fecha_inicio_inscripciones', 'desc');

            // Aplicar filtros
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('codigo', 'like', "%{$searchTerm}%")
                        ->orWhereHas('programa', function ($q2) use ($searchTerm) {
                            $q2->where('nombre', 'like', "%{$searchTerm}%");
                        });
                });
            }

            if ($request->filled('sucursal_id')) {
                $query->where('sucursale_id', $request->sucursal_id);
            }

            // Paginación
            $perPage = $request->get('per_page', 10);
            $ofertas = $query->paginate($perPage);

            // Procesar cada oferta
            $ofertas->getCollection()->transform(function ($oferta) use ($cargoPrincipal) {
                try {
                    // Generar enlace personalizado
                    $enlacePersonalizado = route('oferta.asesor', [
                        'id' => $oferta->id,
                        'asesorId' => $cargoPrincipal->id
                    ]);

                    $oferta->enlace_personalizado = $enlacePersonalizado;
                    $oferta->enlace_qr = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" .
                        urlencode($enlacePersonalizado);

                    // Información adicional
                    $oferta->programa_nombre = $oferta->programa->nombre ?? 'Sin programa';
                    $oferta->sucursal_nombre = $oferta->sucursal->nombre ?? 'Sin sucursal';
                    $oferta->sede_nombre = optional($oferta->sucursal->sede)->nombre ?? 'Sin sede';
                    $oferta->modalidad_nombre = $oferta->modalidad->nombre ?? 'Sin modalidad';
                    $oferta->fase_nombre = $oferta->fase->nombre ?? 'Sin fase';

                    // Formatear fechas
                    if ($oferta->fecha_inicio_inscripciones) {
                        $oferta->fecha_inicio_formateada = \Carbon\Carbon::parse($oferta->fecha_inicio_inscripciones)
                            ->format('d/m/Y');
                    } else {
                        $oferta->fecha_inicio_formateada = 'Sin fecha';
                    }

                    if ($oferta->fecha_fin_inscripciones) {
                        $oferta->fecha_fin_formateada = \Carbon\Carbon::parse($oferta->fecha_fin_inscripciones)
                            ->format('d/m/Y');
                    } else {
                        $oferta->fecha_fin_formateada = 'Sin fecha';
                    }
                } catch (\Exception $e) {
                    \Log::error('Error procesando oferta ID ' . $oferta->id . ': ' . $e->getMessage());
                    $oferta->enlace_personalizado = '#';
                    $oferta->enlace_qr = '';
                    $oferta->fecha_inicio_formateada = 'Error';
                    $oferta->fecha_fin_formateada = 'Error';
                }

                return $oferta;
            });

            return response()->json([
                'success' => true,
                'ofertas' => $ofertas,
                'cargo_principal' => [
                    'id' => $cargoPrincipal->id,
                    'cargo_nombre' => $cargoPrincipal->cargo->nombre ?? 'Sin cargo',
                    'sucursal_nombre' => optional($cargoPrincipal->sucursal)->nombre ?? 'Sin sucursal'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getOfertasMarketingActivas: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('La contraseña actual es incorrecta.');
                    }
                }
            ],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/'
            ],
            'new_password_confirmation' => 'required|same:new_password',
        ], [
            'new_password.regex' => 'La contraseña debe tener al menos 8 caracteres y contener letras y números.',
            'new_password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Actualizar la contraseña
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Cerrar sesión en otros dispositivos si se desea (opcional)
        // Auth::logoutOtherDevices($request->new_password);

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente.'
        ]);
    }
}
