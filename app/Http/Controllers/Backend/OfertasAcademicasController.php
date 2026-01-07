<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Ciudade;
use App\Models\Concepto;
use App\Models\Convenio;
use App\Models\Departamento;
use App\Models\Fase;
use App\Models\GradosAcademico;
use App\Models\Horario;
use App\Models\Matriculacione;
use App\Models\Modalidade;
use App\Models\Modulo;
use App\Models\OfertasAcademica;
use App\Models\PlanesPago;
use App\Models\Profesione;
use App\Models\Sede;
use App\Models\Sucursale;
use App\Models\SucursalesCuenta;
use App\Models\TrabajadoresCargo;
use App\Models\Universidade;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class OfertasAcademicasController extends Controller
{
    // En OfertasAcademicasController.php

    public function listar(Request $request)
    {
        // Construir la consulta base
        $query = OfertasAcademica::with([
            'posgrado.area',
            'posgrado.tipo',
            'posgrado.convenio',
            'sucursal.sede',
            'modalidad',
            'programa',
            'fase',
            'modulos'
        ]);

        // Aplicar filtros
        if ($request->filled('sucursale_id')) {
            $query->where('sucursale_id', $request->sucursale_id);
        }

        if ($request->filled('convenio_id')) {
            $query->whereHas('posgrado', function ($q) use ($request) {
                $q->where('convenio_id', $request->convenio_id);
            });
        }

        if ($request->filled('fase_id')) {
            $query->where('fase_id', $request->fase_id);
        }

        if ($request->filled('modalidade_id')) {
            $query->where('modalidade_id', $request->modalidade_id);
        }

        $ofertas = $query->orderBy('created_at', 'desc')->paginate(20);

        // **MODIFICACIÓN IMPORTANTE**: Siempre devolver JSON para solicitudes AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.ofertas.partials.table-body', compact('ofertas'))->render(),
                'pagination' => $ofertas->links('pagination::bootstrap-5')->toHtml(),
                'total' => $ofertas->total()
            ]);
        }

        // Si NO es AJAX, cargar todos los datos necesarios
        $trabajadoresCargos = TrabajadoresCargo::with(['trabajador.persona', 'cargo'])
            ->where('estado', 'Vigente')
            ->get();

        $planesPagosTodos = PlanesPago::all();
        $conceptos = Concepto::all();
        $departamentos = Departamento::all();
        $ciudades = Ciudade::all();
        $grados = GradosAcademico::all();
        $universidades = Universidade::all();
        $profesiones = Profesione::all();

        // Nuevos datos para filtros
        $convenios = Convenio::all();
        $fases = Fase::all();
        $modalidades = Modalidade::all();
        $sucursales = Sucursale::with('sede')->get();
        $sedes = Sede::all();

        return view('admin.ofertas.listar', [
            'ofertas' => $ofertas,
            'sucursales' => $sucursales,
            'sedes' => $sedes,
            'modalidades' => $modalidades,
            'planesPagos' => PlanesPago::all(),
            'conceptos' => $conceptos,
            'trabajadoresAcademicos' => $trabajadoresCargos,
            'trabajadoresMarketing' => $trabajadoresCargos,
            'planesPagosTodos' => $planesPagosTodos,
            'departamentos' => $departamentos,
            'ciudades' => $ciudades,
            'grados' => $grados,
            'universidades' => $universidades,
            'profesiones' => $profesiones,
            'convenios' => $convenios,
            'fases' => $fases,
        ]);
    }

    public function detallePublico($id)
    {
        $oferta = OfertasAcademica::with([
            'sucursal.sede',
            'modalidad',
            'posgrado.area',
            'posgrado.convenio',
            'posgrado.tipo',
            'fase',
            'plan_concepto.plan_pago',
            'plan_concepto.concepto',
            'modulos' => function ($query) {
                $query->orderBy('n_modulo', 'asc');
            },
            'modulos.horarios',
            'modulos.docente.persona',
            'inscripciones' => function ($query) {
                $query->where('estado', 'Inscrito')->count();
            }
        ])->findOrFail($id);

        // Obtener el plan "Al Contado" para mostrar el precio
        $planAlContado = $oferta->plan_concepto->firstWhere('plan_pago.nombre', 'Al Contado');

        // Obtener trabajadores responsables si existen
        $responsableMarketing = null;
        $responsableAcademico = null;

        if ($oferta->responsable_marketing_cargo_id) {
            $responsableMarketing = TrabajadoresCargo::with(['trabajador.persona', 'cargo'])
                ->find($oferta->responsable_marketing_cargo_id);
        }

        if ($oferta->responsable_academico_cargo_id) {
            $responsableAcademico = TrabajadoresCargo::with(['trabajador.persona', 'cargo'])
                ->find($oferta->responsable_academico_cargo_id);
        }

        return view('oferta-detalle', compact(
            'oferta',
            'planAlContado',
            'responsableMarketing',
            'responsableAcademico'
        ));
    }

    public function obtenerPlanesPagoOferta($id)
    {
        $oferta = OfertasAcademica::with([
            'plan_concepto.plan_pago',
            'plan_concepto.concepto'
        ])->findOrFail($id);

        // Agrupar por plan de pago
        $planesAgrupados = [];
        $now = now()->format('Y-m-d');

        foreach ($oferta->plan_concepto as $pc) {
            $planId = $pc->planes_pago_id;
            $planNombre = $pc->plan_pago->nombre ?? 'Sin nombre';

            if (!isset($planesAgrupados[$planId])) {
                $planesAgrupados[$planId] = [
                    'nombre' => $planNombre,
                    'conceptos' => []
                ];
            }

            // Verificar si es promoción y si está vigente
            $esPromocionVigente = false;
            $precioRegular = null;
            $descuento = null;

            if ($pc->es_promocion && $pc->fecha_inicio_promocion && $pc->fecha_fin_promocion) {
                $inicio = $pc->fecha_inicio_promocion;
                $fin = $pc->fecha_fin_promocion;
                $esPromocionVigente = ($now >= $inicio && $now <= $fin);
                $precioRegular = $pc->precio_regular;
                $descuento = $pc->descuento_porcentaje;
            }

            // Calcular el monto por cuota (pago_bs / n_cuotas)
            $montoPorCuota = $pc->n_cuotas > 0 ? $pc->pago_bs / $pc->n_cuotas : $pc->pago_bs;

            $planesAgrupados[$planId]['conceptos'][] = [
                'concepto_nombre' => $pc->concepto->nombre ?? 'Sin concepto',
                'n_cuotas' => $pc->n_cuotas,
                'monto_por_cuota' => number_format($montoPorCuota, 2),
                'total_concepto' => number_format($pc->pago_bs, 2),
                'es_promocion' => $pc->es_promocion,
                'promocion_vigente' => $esPromocionVigente,
                'fecha_inicio_promocion' => $pc->fecha_inicio_promocion,
                'fecha_fin_promocion' => $pc->fecha_fin_promocion,
                'precio_regular' => $precioRegular ? number_format($precioRegular, 2) : null,
                'descuento_porcentaje' => $descuento,
                'descuento_bs' => $precioRegular ? number_format($precioRegular - $pc->pago_bs, 2) : null
            ];
        }

        return response()->json([
            'success' => true,
            'planes' => array_values($planesAgrupados),
            'total_planes' => count($planesAgrupados)
        ]);
    }


    public function ofertaConAsesor($id, $asesorId)
    {
        // Obtener la oferta académica
        $oferta = OfertasAcademica::with([
            'sucursal.sede',
            'modalidad',
            'posgrado.area',
            'posgrado.convenio',
            'posgrado.tipo',
            'fase',
            'plan_concepto.plan_pago',
            'plan_concepto.concepto',
            'modulos' => function ($query) {
                $query->orderBy('n_modulo', 'asc');
            },
            'modulos.horarios',
            'modulos.docente.persona',
            'inscripciones' => function ($query) {
                $query->where('estado', 'Inscrito')->count();
            }
        ])->findOrFail($id);

        // Obtener el asesor (trabajador con cargo)
        $asesor = TrabajadoresCargo::with(['trabajador.persona', 'cargo', 'sucursal.sede'])
            ->where('id', $asesorId)
            ->where('estado', 'Vigente')
            ->firstOrFail();

        // Filtrar planes de pago para mostrar solo "Al Credito"
        $planesCredito = $oferta->plan_concepto->filter(function ($planConcepto) {
            $planNombre = $planConcepto->plan_pago->nombre ?? '';
            return stripos($planNombre, 'Al Credito') !== false ||
                stripos($planNombre, 'Al Crédito') !== false;
        });

        // Calcular totales para el plan de crédito
        $totalMensualCredito = 0;
        $totalCuotasCredito = 1;
        $totalProgramaCredito = 0;

        if ($planesCredito->isNotEmpty()) {
            $totalMensualCredito = $planesCredito->sum('pago_bs');
            $primerPlan = $planesCredito->first();
            $totalCuotasCredito = $primerPlan->n_cuotas ?? 1;
            $totalProgramaCredito = $totalMensualCredito * $totalCuotasCredito;
        }

        // También obtener plan al contado para comparación si existe
        $planContado = $oferta->plan_concepto->first(function ($planConcepto) {
            $planNombre = $planConcepto->plan_pago->nombre ?? '';
            return stripos($planNombre, 'contado') !== false;
        });

        $totalContado = $planContado ? $planContado->pago_bs : 0;
        $ahorroMensual = $totalContado > 0 ? $totalContado - $totalMensualCredito : 0;

        // Obtener responsables del programa
        $responsableMarketing = null;
        $responsableAcademico = null;

        if ($oferta->responsable_marketing_cargo_id) {
            $responsableMarketing = TrabajadoresCargo::with(['trabajador.persona', 'cargo'])
                ->find($oferta->responsable_marketing_cargo_id);
        }

        if ($oferta->responsable_academico_cargo_id) {
            $responsableAcademico = TrabajadoresCargo::with(['trabajador.persona', 'cargo'])
                ->find($oferta->responsable_academico_cargo_id);
        }

        // Agregar estas variables para el formulario
        $departamentos = Departamento::all();
        $ciudadesPorDepartamento = Ciudade::with('departamento')->get()->toArray();

        return view('oferta-asesor', compact(
            'oferta',
            'asesor',
            'planesCredito',
            'totalMensualCredito',
            'totalCuotasCredito',
            'totalProgramaCredito',
            'totalContado',
            'ahorroMensual',
            'responsableMarketing',
            'responsableAcademico',
            'departamentos',
            'ciudadesPorDepartamento' // Nueva variable para JavaScript
        ));
    }

    // En OfertasAcademicasController.php - agregar este método
    // En OfertasAcademicasController.php - actualiza el método dashboardOferta
    public function dashboardOferta($id)
    {
        $oferta = OfertasAcademica::with([
            'programa',
            'modulos',
            'sucursal.sede',
            'posgrado.convenio',
            'modalidad',
            'fase',
            'inscripciones.estudiante.persona.ciudad',
            'inscripciones.cuotas',
            'inscripciones.matriculaciones.modulo',
            'inscripciones.planesPago'
        ])->findOrFail($id);

        // 1. Estadísticas de inscritos
        $totalInscritos = $oferta->inscripciones->where('estado', 'Inscrito')->count();
        $totalPreInscritos = $oferta->inscripciones->where('estado', 'Pre-Inscrito')->count();

        // Gráfico de inscripciones por mes
        $inscripcionesPorMes = $this->getInscripcionesPorMes($oferta);

        // 2. Ingresos por conceptos
        $ingresosPorConcepto = $this->getIngresosPorConcepto($oferta);

        // 3. Tabla académica con notas
        $tablaAcademica = $this->getTablaAcademica($oferta);

        // 4. Datos adicionales para estadísticas
        $totalRecaudado = $oferta->inscripciones->sum(function ($inscripcion) {
            return $inscripcion->cuotas->sum(function ($cuota) {
                return $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
            });
        });

        $totalDeuda = $oferta->inscripciones->sum(function ($inscripcion) {
            return $inscripcion->cuotas->sum('pago_pendiente_bs');
        });

        // 5. Nuevas estadísticas
        // Por sexo
        $inscripcionesActivas = $oferta->inscripciones->where('estado', 'Inscrito');
        $hombres = 0;
        $mujeres = 0;

        // Promedio de edad y ciudades
        $edades = [];
        $ciudades = [];

        foreach ($inscripcionesActivas as $inscripcion) {
            $persona = $inscripcion->estudiante->persona;

            // Conteo por sexo
            if ($persona->sexo === 'Hombre') {
                $hombres++;
            } elseif ($persona->sexo === 'Mujer') {
                $mujeres++;
            }

            // Calcular edad si tiene fecha de nacimiento
            if ($persona->fecha_nacimiento) {
                $edad = now()->diffInYears($persona->fecha_nacimiento);
                $edades[] = $edad;
            }

            // Conteo por ciudad
            $ciudadNombre = $persona->ciudad->nombre ?? 'Sin ciudad';
            if (!isset($ciudades[$ciudadNombre])) {
                $ciudades[$ciudadNombre] = 0;
            }
            $ciudades[$ciudadNombre]++;
        }

        $promedioEdad = count($edades) > 0 ? array_sum($edades) / count($edades) : 0;

        // Ordenar ciudades por cantidad descendente y tomar las top 5
        arsort($ciudades);
        $topCiudades = array_slice($ciudades, 0, 5, true);

        return view('admin.ofertas.dashboard', compact(
            'oferta',
            'totalInscritos',
            'totalPreInscritos',
            'inscripcionesPorMes',
            'ingresosPorConcepto',
            'tablaAcademica',
            'totalRecaudado',
            'totalDeuda',
            'hombres',
            'mujeres',
            'promedioEdad',
            'topCiudades'
        ));
    }

    // En OfertasAcademicasController.php - agregar este método
    // En OfertasAcademicasController.php - método actualizarPlanesPago (VERSIÓN CORREGIDA)
    public function actualizarPlanesPago(Request $request, $id)
    {
        $oferta = OfertasAcademica::findOrFail($id);

        // Validar que la oferta esté en fase 2
        if ($oferta->fase_id != 2) {
            return response()->json([
                'success' => false,
                'msg' => 'Solo se pueden modificar los planes de pago en fase 2.'
            ], 422);
        }

        $request->validate([
            'planes' => 'required|array|min:1',
            'planes.*.planes_pago_id' => 'required|exists:planes_pagos,id', // ID del plan existente
            'planes.*.conceptos' => 'required|array|min:1',
            'planes.*.conceptos.*.concepto_id' => 'required|exists:conceptos,id',
            'planes.*.conceptos.*.n_cuotas' => 'required|integer|min:1',
            'planes.*.conceptos.*.pago_bs' => 'required|numeric|min:0',
        ]);

        // Eliminar los planes_concepto existentes para esta oferta
        $oferta->plan_concepto()->delete();

        // Crear los nuevos planes (usando el plan de pago existente)
        foreach ($request->planes as $planData) {
            // Usar el plan de pago existente (NO crear uno nuevo)
            $planPago = PlanesPago::find($planData['planes_pago_id']);

            if (!$planPago) {
                continue; // Saltar si no existe el plan
            }

            foreach ($planData['conceptos'] as $conceptoData) {
                $oferta->plan_concepto()->create([
                    'planes_pago_id' => $planPago->id,
                    'concepto_id' => $conceptoData['concepto_id'],
                    'n_cuotas' => $conceptoData['n_cuotas'],
                    'pago_bs' => $conceptoData['pago_bs'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'msg' => 'Planes de pago actualizados correctamente.'
        ]);
    }

    // Método auxiliar para obtener inscripciones por mes
    private function getInscripcionesPorMes(OfertasAcademica $oferta)
    {
        $inscripciones = $oferta->inscripciones()
            ->selectRaw('MONTH(fecha_registro) as mes, estado, COUNT(*) as total')
            ->whereYear('fecha_registro', date('Y'))
            ->groupBy('mes', 'estado')
            ->get();

        $meses = [];
        for ($i = 1; $i <= 12; $i++) {
            $meses[$i] = [
                'Inscrito' => 0,
                'Pre-Inscrito' => 0
            ];
        }

        foreach ($inscripciones as $ins) {
            $meses[$ins->mes][$ins->estado] = $ins->total;
        }

        return $meses;
    }

    // Método auxiliar para ingresos por concepto
    private function getIngresosPorConcepto(OfertasAcademica $oferta)
    {
        $conceptos = ['Matrícula', 'Colegiatura', 'Certificación'];
        $resultados = [];

        foreach ($conceptos as $conceptoNombre) {
            $cobrado = 0;
            $deuda = 0;

            foreach ($oferta->inscripciones as $inscripcion) {
                // Filtrar cuotas por concepto (usando el nombre)
                $cuotasConcepto = $inscripcion->cuotas->filter(function ($cuota) use ($conceptoNombre) {
                    return stripos($cuota->nombre, $conceptoNombre) !== false;
                });

                $cobrado += $cuotasConcepto->sum(function ($cuota) {
                    return $cuota->pago_total_bs - $cuota->pago_pendiente_bs;
                });

                $deuda += $cuotasConcepto->sum('pago_pendiente_bs');
            }

            $resultados[] = [
                'concepto' => $conceptoNombre,
                'cobrado' => $cobrado,
                'deuda' => $deuda,
                'total' => $cobrado + $deuda
            ];
        }

        return $resultados;
    }

    // Método auxiliar para tabla académica
    private function getTablaAcademica(OfertasAcademica $oferta)
    {
        // Cargar todas las inscripciones con sus relaciones necesarias
        $inscripciones = $oferta->inscripciones()
            ->where('estado', 'Inscrito')
            ->with([
                'estudiante.persona',
                'matriculaciones' => function ($query) {
                    $query->with('modulo');
                }
            ])
            ->get();

        $tabla = [];

        foreach ($inscripciones as $inscripcion) {
            $fila = [
                'estudiante_id' => $inscripcion->estudiante->id,
                'estudiante' => trim($inscripcion->estudiante->persona->nombres . ' ' .
                    $inscripcion->estudiante->persona->apellido_paterno),
                'carnet' => $inscripcion->estudiante->persona->carnet,
                'modulos' => []
            ];

            // Organizar notas por módulo
            foreach ($inscripcion->matriculaciones as $matriculacion) {
                if ($matriculacion->modulo) {
                    $fila['modulos'][$matriculacion->modulo->nombre] = [
                        'nota_regular' => $matriculacion->nota_regular,
                        'nota_nivelacion' => $matriculacion->nota_nivelacion
                    ];
                }
            }

            $tabla[] = $fila;
        }

        return $tabla;
    }

    public function detalleModulo($ofertaId, $moduloId)
    {
        $modulo = Modulo::with([
            'oferta_academica.programa',
            'docente.persona',
            'horarios.trabajador_cargo.trabajador.persona',
            'horarios.sucursal_cuenta.cuenta',
            'matriculaciones.inscripcion.estudiante.persona'
        ])->where('id', $moduloId)
            ->where('ofertas_academica_id', $ofertaId)
            ->firstOrFail();

        // Obtener todas las inscripciones del módulo con sus notas
        $matriculaciones = Matriculacione::with([
            'inscripcion.estudiante.persona'
        ])->where('modulo_id', $moduloId)->get();

        return view('admin.ofertas.detalle-modulo', compact('modulo', 'matriculaciones'));
    }

    public function obtenerOfertaParaEdicion($id)
    {
        $oferta = OfertasAcademica::with([
            'modulos',
            'plan_concepto',
            'sucursal.sede',
            'modalidad',
            'programa',
            'fase'
        ])->findOrFail($id);

        return response()->json($oferta);
    }

    public function vermodulos($id)
    {
        $oferta = OfertasAcademica::with([
            'modulos' => function ($query) {
                $query->with('horarios.trabajador_cargo.trabajador.persona', 'horarios.sucursal_cuenta.cuenta');
            }
        ])->findOrFail($id);

        // Cargar datos para el modal (como antes)
        $trabajadoresCargos = TrabajadoresCargo::with(['trabajador.persona', 'cargo'])
            ->where('sucursale_id', $oferta->sucursale_id)
            ->where('estado', 'Vigente')
            ->get();
        $sucursalesCuentas = SucursalesCuenta::with(['cuenta'])
            ->where('sucursale_id', $oferta->sucursale_id)
            ->get();

        // === CATALOGOS PARA EL FORMULARIO DE DOCENTE ===
        $departamentos = Departamento::all();
        $ciudades = Ciudade::with('departamento')->get();

        $grados = GradosAcademico::all();
        $profesiones = Profesione::all();
        $universidades = Universidade::all();

        return view('admin.ofertas.vermodulos', compact(
            'oferta',
            'trabajadoresCargos',
            'sucursalesCuentas',
            'departamentos',
            'ciudades',        // <-- Añadido
            'grados',
            'profesiones',
            'universidades'
        ));
    }

    // Este método devuelve los eventos del módulo en el formato que necesita FullCalendar
    public function obtenerEventosModulo(Modulo $modulo)
    {
        $eventos = [];
        foreach ($modulo->horarios as $horario) {
            $start = $horario->fecha . 'T' . $horario->hora_inicio;
            $end = $horario->fecha . 'T' . $horario->hora_fin;
            $responsable = optional($horario->trabajador_cargo)->trabajador?->persona?->nombres . ' ' .
                optional($horario->trabajador_cargo)->trabajador?->persona?->apellido_paterno ?? 'Sin responsable';
            $cargo = optional($horario->trabajador_cargo)->cargo?->nombre ?? '';
            $estado = $horario->estado ?? 'Confirmado';
            $estadoLabel = match ($estado) {
                'Confirmado' => '[✅ Confirmado]',
                'Desarrollado' => '[✔️ Desarrollado]',
                'Postergado' => '[⏸️ Postergado]',
                default => '',
            };
            $title = $modulo->nombre . ' - ' . $responsable . ' (' . $cargo . ') ' . $estadoLabel;

            $eventos[] = [
                'title' => $title,
                'start' => $start,
                'end' => $end,
                'className' => 'text-with',
                'extendedProps' => [
                    'modulo_id' => $modulo->id,
                    'horario_id' => $horario->id,
                    'responsable' => $responsable,
                    'cargo' => $cargo,
                    'estado' => $estado,
                    'color_modulo' => $modulo->color,
                ]
            ];
        }

        return response()->json($eventos); // Devuelve un array plano de eventos
    }

    // En OfertasAcademicasController.php
    public function reprogramarHorario(Request $request)
    {
        $request->validate([
            'horario_original_id' => 'required|exists:horarios,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
        ]);

        $horarioOriginal = Horario::findOrFail($request->horario_original_id);

        // Crear el nuevo horario reprogramado
        $nuevoHorario = Horario::create([
            'modulo_id' => $horarioOriginal->modulo_id,
            'trabajadores_cargo_id' => $horarioOriginal->trabajadores_cargo_id,
            'sucursales_cuenta_id' => $horarioOriginal->sucursales_cuenta_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'estado' => 'Confirmado', // El nuevo horario inicia como Confirmado
            'reprogramado_id' => $horarioOriginal->id, // Relación clave
        ]);

        // Opcional: Cambiar el estado del horario original a "Reprogramado"
        // $horarioOriginal->update(['estado' => 'Reprogramado']);

        return response()->json([
            'success' => true,
            'msg' => 'Sesión reprogramada correctamente.',
            'nuevo_horario' => $nuevoHorario
        ]);
    }

    // Este método se usa para cargar los datos en el modal de "Asignar Horarios"
    public function obtenerHorarios(Modulo $modulo)
    {
        $horarios = $modulo->horarios()->with([
            'trabajador_cargo',
            'sucursal_cuenta'
        ])->get();

        $trabajadores_cargo_id = $horarios->isNotEmpty() ? $horarios->first()->trabajadores_cargo_id : null;
        $sucursales_cuenta_id = $horarios->isNotEmpty() ? $horarios->first()->sucursales_cuenta_id : null;

        return response()->json([
            'horarios' => $horarios,
            'trabajadores_cargo_id' => $trabajadores_cargo_id,
            'sucursales_cuenta_id' => $sucursales_cuenta_id,
            'cantidad_sesiones' => $horarios->count()
        ]);
    }



    public function actualizarColorModulo(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:modulos,id',
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);

        $modulo = Modulo::findOrFail($request->id);
        $modulo->color = $request->color;
        $modulo->save();

        return response()->json(['success' => true]);
    }

    public function actualizarEstadoHorario(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:horarios,id',
            'estado' => 'required|in:Confirmado,Desarrollado,Postergado'
        ]);

        $horario = Horario::findOrFail($request->id);
        $horario->estado = $request->estado;
        $horario->save();

        return response()->json(['success' => true]);
    }

    // Modifica el método `asignarHorarios` para que devuelva los eventos
    public function asignarHorarios(Request $request)
    {
        $request->validate([
            'modulo_id' => 'required|exists:modulos,id',
            //'trabajadores_cargo_id' => 'required|exists:trabajadores_cargos,id',
            //'sucursales_cuenta_id' => 'required|exists:sucursales_cuentas,id',
            'horarios' => 'required|array|min:1',
            'horarios.*.fecha' => 'required|date',
            'horarios.*.hora_inicio' => 'required',
            'horarios.*.hora_fin' => 'required|after:horarios.*.hora_inicio',
            'horarios.*.estado' => 'required|in:Confirmado,Desarrollado,Postergado',
        ]);

        $modulo = Modulo::findOrFail($request->modulo_id);
        // Eliminar horarios anteriores para reemplazarlos
        $modulo->horarios()->delete();

        foreach ($request->horarios as $h) {
            Horario::create([
                'modulo_id' => $modulo->id,
                'trabajadores_cargo_id' => $request->trabajadores_cargo_id,
                'sucursales_cuenta_id' => $request->sucursales_cuenta_id,
                'fecha' => $h['fecha'],
                'hora_inicio' => $h['hora_inicio'],
                'hora_fin' => $h['hora_fin'],
                'estado' => $h['estado'] ?? 'Confirmado',
            ]);
        }

        // Devolver los nuevos eventos para el calendario
        $eventos = [];
        foreach ($modulo->fresh()->horarios as $horario) {
            $start = $horario->fecha . 'T' . $horario->hora_inicio;
            $end = $horario->fecha . 'T' . $horario->hora_fin;
            $responsable = optional($horario->trabajador_cargo)->trabajador?->persona?->nombres . ' ' .
                optional($horario->trabajador_cargo)->trabajador?->persona?->apellido_paterno ?? 'Sin responsable';
            $cargo = optional($horario->trabajador_cargo)->cargo?->nombre ?? '';
            $estado = $horario->estado ?? 'Confirmado';
            $estadoLabel = match ($estado) {
                'Confirmado' => '[✅ Confirmado]',
                'Desarrollado' => '[✔️ Desarrollado]',
                'Postergado' => '[⏸️ Postergado]',
                default => '',
            };
            $title = $modulo->nombre . ' - ' . $responsable . ' (' . $cargo . ') ' . $estadoLabel;

            $eventos[] = [
                'title' => $title,
                'start' => $start,
                'end' => $end,
                'className' => 'text-with',
                'extendedProps' => [
                    'modulo_id' => $modulo->id,
                    'horario_id' => $horario->id,
                    'responsable' => $responsable,
                    'cargo' => $cargo,
                    'estado' => $estado,
                    'color_modulo' => $modulo->color,
                ]
            ];
        }

        return response()->json([
            'success' => true,
            'msg' => 'Horarios asignados correctamente.',
            'eventos' => $eventos
        ]);
    }

    public function registrar(Request $request)
    {
        $validated = $request->validate([
            'posgrado_id' => 'required|exists:posgrados,id',
            'sucursale_id' => 'required|exists:sucursales,id',
            'modalidade_id' => 'required|exists:modalidades,id',
            'programa_id' => 'required|exists:programas,id',
            'responsable_academico_cargo_id' => 'required|exists:trabajadores_cargos,id',
            'responsable_marketing_cargo_id' => 'required|exists:trabajadores_cargos,id',
            'codigo' => 'required|string|unique:ofertas_academicas,codigo',
            'gestion' => 'required|string',
            'color' => 'required|string',
            'fecha_inicio_inscripciones' => 'required|date|after:today',
            'fecha_inicio_programa' => 'required|date|after:fecha_inicio_inscripciones',
            'fecha_fin_programa' => 'required|date|after:fecha_inicio_programa',
            'n_modulos' => 'nullable|integer|min:0',
            'cantidad_sesiones' => 'nullable|integer|min:1',
            'version' => 'nullable|string|max:50',
            'grupo' => 'nullable|string|max:50',
            'nota_minima' => 'nullable|numeric|min:0|max:100',
            'portada' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'certificado' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Valores por defecto
        $validated['gestion'] = $validated['gestion'] ?? date('Y');
        $validated['n_modulos'] = $validated['n_modulos'] ?? 1;
        $validated['version'] = $validated['version'] ?? '1';
        $validated['grupo'] = $validated['grupo'] ?? '1';
        $validated['cantidad_sesiones'] = $validated['cantidad_sesiones'] ?? '1';
        $validated['nota_minima'] = $validated['nota_minima'] ?? 61;
        $validated['fase_id'] = '1';
        $validated['color'] = $validated['color'] ?? '#ccc';

        // Directorios
        $portadaDir = public_path('upload/programas/portada');
        $certificadoDir = public_path('upload/programas/certificado');

        // Crear directorios si no existen
        if (!File::exists($portadaDir)) File::makeDirectory($portadaDir, 0755, true);
        if (!File::exists($certificadoDir)) File::makeDirectory($certificadoDir, 0755, true);

        // === Subir PORTADA ===
        if ($request->hasFile('portada')) {
            $file = $request->file('portada');
            $filename = 'portada_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $portadaDir . '/' . $filename;

            $image = Image::make($file)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($path, 85);

            $validated['portada'] = 'upload/programas/portada/' . $filename;
        }

        // === Subir CERTIFICADO ===
        if ($request->hasFile('certificado')) {
            $file = $request->file('certificado');
            $filename = 'certificado_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $certificadoDir . '/' . $filename;

            $image = Image::make($file)->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($path, 85);

            $validated['certificado'] = 'upload/programas/certificado/' . $filename;
        }

        // Crear la oferta
        $oferta = OfertasAcademica::create($validated);

        // === Guardar MÓDULOS ===
        if ($request->has('modulos')) {
            foreach ($request->modulos as $moduloData) {
                $oferta->modulos()->create([
                    'n_modulo' => $moduloData['n_modulo'],
                    'nombre' => $moduloData['nombre'],
                    'fecha_inicio' => $moduloData['fecha_inicio'],
                    'fecha_fin' => $moduloData['fecha_fin'],
                ]);
            }
        }

        // Guardar planes de pago
        if ($request->has('planes')) {
            foreach ($request->planes as $planPagoId => $conceptos) {
                // Asegurar que el ID del plan de pago sea un entero
                $planPagoId = filter_var($planPagoId, FILTER_VALIDATE_INT);
                if ($planPagoId === false || $planPagoId <= 0) {
                    continue; // Saltar si no es un ID válido
                }

                if (!is_array($conceptos)) continue;

                foreach ($conceptos as $conceptoData) {
                    if (empty($conceptoData['concepto_id'])) continue;

                    $oferta->plan_concepto()->create([
                        'planes_pago_id' => $planPagoId,
                        'concepto_id' => $conceptoData['concepto_id'],
                        'n_cuotas' => isset($conceptoData['n_cuotas']) ? (int) $conceptoData['n_cuotas'] : 1,
                        'pago_bs' => isset($conceptoData['pago_bs']) ? (float) $conceptoData['pago_bs'] : 0.0,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'msg' => 'Oferta académica registrada correctamente.'
        ]);
    }

    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
        ]);

        $exists = OfertasAcademica::where('codigo', $request->codigo)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function cronograma()
    {
        $sedes = Sede::with('sucursales')->get();
        $ofertas = collect(); // Vacío al inicio
        return view('admin.ofertas.cronograma', compact('sedes', 'ofertas'));
    }

    public function obtenerEventosCronograma(Request $request)
    {
        $query = OfertasAcademica::query()
            ->with([
                'modulos.horarios.trabajador_cargo.trabajador.persona',
                'modulos.horarios.sucursal_cuenta.cuenta',
                'sucursal.sede',
                'programa'
            ]);

        // Si se pasa oferta_id, ignorar sede/sucursal y filtrar solo por esa oferta
        if ($request->filled('oferta_id')) {
            $query->where('id', $request->oferta_id);
        } else {
            // Filtrar por sede o sucursal como antes
            if ($request->filled('sucursale_id')) {
                $query->where('sucursale_id', $request->sucursale_id);
            } elseif ($request->filled('sede_id')) {
                $sucursalIds = Sucursale::where('sede_id', $request->sede_id)->pluck('id');
                $query->whereIn('sucursale_id', $sucursalIds);
            }
        }

        $ofertas = $query->get();

        $eventos = [];
        foreach ($ofertas as $oferta) {
            foreach ($oferta->modulos as $modulo) {
                foreach ($modulo->horarios as $horario) {
                    $start = $horario->fecha . 'T' . $horario->hora_inicio;
                    $end = $horario->fecha . 'T' . $horario->hora_fin;

                    $responsable = optional($horario->trabajador_cargo)->trabajador?->persona?->nombres . ' ' .
                        optional($horario->trabajador_cargo)->trabajador?->persona?->apellido_paterno ?? 'Sin responsable';
                    $cargo = optional($horario->trabajador_cargo)->cargo?->nombre ?? '';
                    $estado = $horario->estado ?? 'Confirmado';

                    $estadoLabel = match ($estado) {
                        'Confirmado' => '[✅ Confirmado]',
                        'Desarrollado' => '[✔️ Desarrollado]',
                        'Postergado' => '[⏸️ Postergado]',
                        default => '',
                    };

                    $title = $modulo->nombre . ' - ' . $responsable . ' (' . $cargo . ') ' . $estadoLabel;

                    $eventos[] = [
                        'title' => $title,
                        'start' => $start,
                        'end' => $end,
                        'className' => 'text-with',
                        'extendedProps' => [
                            'oferta_id' => $oferta->id,
                            'oferta_nombre' => $oferta->programa->nombre ?? 'Sin programa',
                            'modulo_id' => $modulo->id,
                            'modulo_nombre' => $modulo->nombre,
                            'horario_id' => $horario->id,
                            'responsable' => $responsable,
                            'cargo' => $cargo,
                            'estado' => $estado,
                            // 👇 Usamos el color de la oferta académica
                            'color_oferta' => $oferta->color ?? '#cccccc',
                            // Opcional: también puedes mantener el color del módulo
                            'color_modulo' => $modulo->color ?? '#eeeeee',
                            'sede' => optional($oferta->sucursal->sede)->nombre ?? 'Sin sede',
                            'sucursal' => optional($oferta->sucursal)->nombre ?? 'Sin sucursal',
                        ]
                    ];
                }
            }
        }

        return response()->json($eventos);
    }

    public function listarJson(Request $request)
    {
        $query = OfertasAcademica::with('programa');
        if ($request->filled('sucursale_id')) {
            $query->where('sucursale_id', $request->sucursale_id);
        }
        return response()->json($query->get(['id', 'programa_id', 'codigo']));
    }

    public function listarOfertasFiltradas(Request $request)
    {
        $query = OfertasAcademica::with(['programa', 'sucursal.sede']);

        if ($request->filled('sucursale_id')) {
            $query->where('sucursale_id', $request->sucursale_id);
        } elseif ($request->filled('sede_id')) {
            $sucursalIds = Sucursale::where('sede_id', $request->sede_id)->pluck('id');
            $query->whereIn('sucursale_id', $sucursalIds);
        } else {
            return response()->json([]);
        }

        return response()->json($query->get()); // El campo `color` ya viene incluido
    }




    // === NUEVO: Cambiar fase ===
    // En OfertasAcademicasController.php - método cambiarFase
    public function cambiarFase(Request $request, OfertasAcademica $oferta)
    {
        $request->validate(['direction' => 'required|in:-1,1']);
        $totalFases = Fase::max('n_fase') ?: 3;
        $nuevaFase = $oferta->fase_id + (int) $request->direction;

        if ($nuevaFase < 1 || $nuevaFase > $totalFases) {
            return response()->json(['success' => false, 'msg' => 'Fase inválida.'], 422);
        }

        // VALIDACIÓN: Si está en fase 2 y quiere pasar a fase 3
        if ($oferta->fase_id == 2 && $nuevaFase == 3) {
            // Verificar si tiene planes de pago registrados
            $tienePlanesPago = $oferta->plan_concepto()->exists();

            if (!$tienePlanesPago) {
                return response()->json([
                    'success' => false,
                    'msg' => '❌ No se puede pasar a la fase de inscripciones.<br><br>' .
                        'Para avanzar a la fase 3 (Inscripciones), primero debe registrar ' .
                        '<strong>al menos un plan de pago</strong> para esta oferta académica.<br><br>' .
                        '<a href="#" class="btn btn-sm btn-info mt-2 ver-planes-btn" ' .
                        'data-oferta-id="' . $oferta->id . '" ' .
                        'data-oferta-codigo="' . $oferta->codigo . '">' .
                        '<i class="ri-eye-line"></i> Ver planes de pago</a>'
                ], 422);
            }
        }

        $oferta->fase_id = $nuevaFase;
        $oferta->save();

        $oferta->load([
            'fase',
            'posgrado.convenio',
            'sucursal.sede',
            'modalidad',
            'programa'
        ]);

        $accionesHtml = view('admin.ofertas.partials.acciones-celda', compact('oferta'))->render();

        return response()->json([
            'success' => true,
            'msg' => 'Fase cambiada exitosamente.',
            'fase_id' => $nuevaFase,
            'fase_nombre' => $oferta->fase?->nombre ?? "Fase {$nuevaFase}",
            'fase_color' => $oferta->fase?->color ?? '#cccccc',
            'bg_color' => $this->hexToRgb($oferta->fase?->color ?? '#cccccc', 0.12),
            'total_fases' => $totalFases,
            'acciones_html' => $accionesHtml,
        ]);
    }



    // === NUEVO: Actualizar oferta ===
    public function actualizar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:ofertas_academicas,id',
            'sucursale_id' => 'required|exists:sucursales,id',
            'modalidade_id' => 'required|exists:modalidades,id',
            'programa_id' => 'required|exists:programas,id',
            'codigo' => 'required|string|unique:ofertas_academicas,codigo,' . $request->id,
            'fecha_inicio_inscripciones' => 'required|date',
            'fecha_inicio_programa' => 'required|date|after:fecha_inicio_inscripciones',
            'fecha_fin_programa' => 'required|date|after:fecha_inicio_programa',
            'n_modulos' => 'nullable|integer|min:1',
            'cantidad_sesiones' => 'nullable|integer|min:1',
            'version' => 'nullable|string',
            'grupo' => 'nullable|string',
            'nota_minima' => 'nullable|numeric|min:1|max:100',
            'color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'portada' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'certificado' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'responsable_academico_cargo_id' => 'required|exists:trabajadores_cargos,id',
            'responsable_marketing_cargo_id' => 'required|exists:trabajadores_cargos,id',
        ]);

        $oferta = OfertasAcademica::findOrFail($request->id);

        // Directorios
        $portadaDir = public_path('upload/programas/portada');
        $certificadoDir = public_path('upload/programas/certificado');

        if (!File::exists($portadaDir)) File::makeDirectory($portadaDir, 0755, true);
        if (!File::exists($certificadoDir)) File::makeDirectory($certificadoDir, 0755, true);

        // === Subir PORTADA (solo si se envía nueva) ===
        if ($request->hasFile('portada')) {
            // Eliminar la anterior si existe
            if ($oferta->portada && File::exists(public_path($oferta->portada))) {
                File::delete(public_path($oferta->portada));
            }
            $file = $request->file('portada');
            $filename = 'portada_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $portadaDir . '/' . $filename;
            $image = Image::make($file)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($path, 85);
            $oferta->portada = 'upload/programas/portada/' . $filename;
        }

        // === Subir CERTIFICADO (solo si se envía nuevo) ===
        if ($request->hasFile('certificado')) {
            if ($oferta->certificado && File::exists(public_path($oferta->certificado))) {
                File::delete(public_path($oferta->certificado));
            }
            $file = $request->file('certificado');
            $filename = 'certificado_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $certificadoDir . '/' . $filename;
            $image = Image::make($file)->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($path, 85);
            $oferta->certificado = 'upload/programas/certificado/' . $filename;
        }

        // Actualizar otros campos
        $oferta->fill($request->except(['_token', 'id', 'modulos', 'planes', 'portada', 'certificado']));
        $oferta->save();

        // Actualizar módulos
        $oferta->modulos()->delete();
        if ($request->has('modulos')) {
            foreach ($request->modulos as $moduloData) {
                $oferta->modulos()->create($moduloData);
            }
        }

        // Actualizar planes de pago
        $oferta->plan_concepto()->delete();
        if ($request->has('planes')) {
            foreach ($request->planes as $planPagoId => $conceptos) {
                if (!is_array($conceptos)) continue;
                foreach ($conceptos as $conceptoData) {
                    if (empty($conceptoData['concepto_id'])) continue;
                    $oferta->plan_concepto()->create([
                        'planes_pago_id' => $planPagoId,
                        'concepto_id' => $conceptoData['concepto_id'],
                        'n_cuotas' => $conceptoData['n_cuotas'] ?? 1,
                        'pago_bs' => $conceptoData['pago_bs'] ?? 0.0,
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'msg' => 'Oferta actualizada correctamente.']);
    }

    // Helper privado
    private function hexToRgb($hex, $alpha = 0.1)
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        $rgb = sscanf($hex, '%02x%02x%02x');
        return "rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, {$alpha})";
    }

    public function obtenerDatosOferta($id)
    {
        $oferta = OfertasAcademica::with([
            'sucursal.sede',
            'modalidad',
            'programa',
            'posgrado',
            'modulos',
            'plan_concepto.concepto',
            'fase'
        ])->findOrFail($id);

        return response()->json($oferta);
    }

    public function agregarPlanPago(Request $request)
    {
        $request->validate([
            'oferta_id' => 'required|exists:ofertas_academicas,id',
            'planes_pago_id' => 'required|exists:planes_pagos,id',
            'conceptos' => 'required|array|min:1',
            'conceptos.*.concepto_id' => 'required|exists:conceptos,id',
            'conceptos.*.n_cuotas' => 'required|integer|min:1',
            'conceptos.*.pago_bs' => 'required|numeric|min:0',
        ]);

        $oferta = OfertasAcademica::findOrFail($request->oferta_id);

        foreach ($request->conceptos as $c) {
            $oferta->plan_concepto()->create([
                'planes_pago_id' => $request->planes_pago_id,
                'concepto_id' => $c['concepto_id'],
                'n_cuotas' => $c['n_cuotas'],
                'pago_bs' => $c['pago_bs'],
            ]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Plan de pago agregado correctamente.'
        ]);
    }

    public function actualizarFase2(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:ofertas_academicas,id',
            'responsable_academico_cargo_id' => 'required|exists:trabajadores_cargos,id',
            'responsable_marketing_cargo_id' => 'required|exists:trabajadores_cargos,id',
            'fecha_inicio_inscripciones' => 'required|date',
            'fecha_inicio_programa' => 'required|date|after:fecha_inicio_inscripciones',
            'fecha_fin_programa' => 'required|date|after:fecha_inicio_programa',
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'portada' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'certificado' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $oferta = OfertasAcademica::findOrFail($request->id);

        // Directorios
        $portadaDir = public_path('upload/programas/portada');
        $certificadoDir = public_path('upload/programas/certificado');
        if (!File::exists($portadaDir)) File::makeDirectory($portadaDir, 0755, true);
        if (!File::exists($certificadoDir)) File::makeDirectory($certificadoDir, 0755, true);

        // Subir portada (opcional)
        if ($request->hasFile('portada')) {
            if ($oferta->portada && File::exists(public_path($oferta->portada))) {
                File::delete(public_path($oferta->portada));
            }
            $file = $request->file('portada');
            $filename = 'portada_' . time() . '.' . $file->getClientOriginalExtension();
            Image::make($file)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($portadaDir . '/' . $filename, 85);
            $oferta->portada = 'upload/programas/portada/' . $filename;
        }

        // Subir certificado (opcional)
        if ($request->hasFile('certificado')) {
            if ($oferta->certificado && File::exists(public_path($oferta->certificado))) {
                File::delete(public_path($oferta->certificado));
            }
            $file = $request->file('certificado');
            $filename = 'certificado_' . time() . '.' . $file->getClientOriginalExtension();
            Image::make($file)->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($certificadoDir . '/' . $filename, 85);
            $oferta->certificado = 'upload/programas/certificado/' . $filename;
        }

        // Solo actualizamos los campos permitidos en fase 2
        $oferta->fill($request->only([
            'responsable_academico_cargo_id',
            'responsable_marketing_cargo_id',
            'fecha_inicio_inscripciones',
            'fecha_inicio_programa',
            'fecha_fin_programa',
            'color'
        ]));

        $oferta->save();

        return response()->json([
            'success' => true,
            'msg' => 'Oferta actualizada correctamente en fase 2.'
        ]);
    }

    public function asignarDocente(Request $request)
    {
        $request->validate([
            'modulo_id' => 'required|exists:modulos,id',
            'docente_id' => 'required|exists:docentes,id',
        ]);

        $modulo = Modulo::findOrFail($request->modulo_id);
        $modulo->docente_id = $request->docente_id;
        $modulo->save();

        return response()->json(['success' => true]);
    }
}
