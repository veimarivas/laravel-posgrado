<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\Ciudade;
use App\Models\CuentasBancarias;
use App\Models\Cuota;
use App\Models\Departamento;
use App\Models\Detalle;
use App\Models\Estudiante;
use App\Models\GradosAcademico;
use App\Models\MovimientosBancarios;
use App\Models\MovimientosCaja;
use App\Models\Pago;
use App\Models\PagosCuota;
use App\Models\Persona;
use App\Models\Profesione;
use App\Models\Universidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class EstudiantesController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');
        $query = Estudiante::with(
            'persona.ciudad.departamento',
            'persona.estudios.grado_academico',
            'persona.estudios.profesion',
            'persona.estudios.universidad'
        );

        if ($search) {
            $query->whereHas('persona', function ($q) use ($search) {
                $q->where('carnet', 'like', "%{$search}%")
                    ->orWhere('nombres', 'like', "%{$search}%")
                    ->orWhere('apellido_paterno', 'like', "%{$search}%")
                    ->orWhere('apellido_materno', 'like', "%{$search}%");
            });
        }

        $estudiantes = $query->paginate(10)->appends(['search' => $search]);

        // Si es una petición AJAX, devolver solo la tabla
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.estudiantes.partials.table-body', compact('estudiantes'))->render(),
                'pagination' => (string) $estudiantes->links('pagination::bootstrap-5'),
                'total' => $estudiantes->total(),
                'from' => $estudiantes->firstItem(),
                'to' => $estudiantes->lastItem()
            ]);
        }

        // Cargar catálogos para el formulario de nueva persona
        $departamentos = Departamento::all();
        $ciudades = Ciudade::with('departamento')->get();
        $grados = GradosAcademico::all();
        $profesiones = Profesione::all();
        $universidades = Universidade::all();

        return view('admin.estudiantes.listar', compact(
            'estudiantes',
            'departamentos',
            'ciudades',
            'grados',
            'profesiones',
            'universidades'
        ));
    }

    // En el método detalle, asegúrate de cargar los pagos relacionados
    public function detalle($id)
    {
        try {
            $estudiante = Estudiante::with([
                'persona.ciudad.departamento',
                'persona.estudios.grado_academico',
                'persona.estudios.profesion',
                'persona.estudios.universidad',
                'inscripciones.ofertaAcademica.programa',
                'inscripciones.ofertaAcademica.modalidad',
                'inscripciones.ofertaAcademica.sucursal',
                'inscripciones.planesPago',
                'inscripciones.trabajador_cargo.trabajador.persona',
                'inscripciones.matriculaciones.modulo.docente.persona',
                'inscripciones.cuotas' => function ($query) {
                    $query->orderBy('n_cuota', 'asc');
                },
                'inscripciones.cuotas.pagos_cuotas.pago.detalles'
            ])->findOrFail($id);

            // Obtener todos los pagos del estudiante para la pestaña historial
            $pagosEstudiante = collect();
            foreach ($estudiante->inscripciones as $inscripcion) {
                foreach ($inscripcion->cuotas as $cuota) {
                    foreach ($cuota->pagos_cuotas as $pagoCuota) {
                        if ($pagoCuota->pago) {
                            $pagosEstudiante->push($pagoCuota->pago);
                        }
                    }
                }
            }
            $pagosEstudiante = $pagosEstudiante->unique('id')->sortByDesc('fecha_pago');

            // Ordenar cuotas para cada inscripción
            foreach ($estudiante->inscripciones as $inscripcion) {
                $inscripcion->cuotas_ordenadas = $this->ordenarCuotas($inscripcion->cuotas);
            }

            return view('admin.estudiantes.detalle', compact('estudiante', 'pagosEstudiante'));
        } catch (\Exception $e) {
            Log::error('Error al cargar detalle de estudiante: ' . $e->getMessage());
            return redirect()->route('admin.estudiantes.listar')
                ->with('error', 'Estudiante no encontrado');
        }
    }

    public function recibosCuota($cuotaId)
    {
        try {
            $cuota = Cuota::with(['pagos_cuotas.pago.detalles'])->findOrFail($cuotaId);

            $html = view('admin.estudiantes.partials.tabla-recibos-cuota', compact('cuota'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Error al cargar los recibos: ' . $e->getMessage()
            ], 500);
        }
    }

    // Agrega este nuevo método al controlador para obtener detalles de pago
    public function obtenerDetallePago($pagoId)
    {
        try {
            \Log::info('Obteniendo detalle del pago ID: ' . $pagoId);

            $pago = Pago::with([
                'pagos_cuotas.cuota.inscripcion.ofertaAcademica.programa',
                'pagos_cuotas.cuota.inscripcion.estudiante.persona',
                'detalles'
            ])->findOrFail($pagoId);

            \Log::info('Pago encontrado: ' . $pago->recibo);

            return response()->json([
                'success' => true,
                'pago' => $pago,
                'cuotas' => $pago->pagos_cuotas,
                'estudiante' => $pago->pagos_cuotas->first()->cuota->inscripcion->estudiante ?? null
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener detalle de pago: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al cargar los detalles del pago: ' . $e->getMessage()
            ], 500);
        }
    }

    // Nuevo método para ordenar cuotas
    private function ordenarCuotas($cuotas)
    {
        // Función para determinar el orden de las cuotas
        $getOrdenCuota = function ($nombreCuota) {
            $nombreLower = mb_strtolower($nombreCuota, 'UTF-8');

            // Verificar si contiene "matricula" (con o sin tilde)
            if (str_contains($nombreLower, 'matricula') || str_contains($nombreLower, 'matrícula')) {
                return 1; // Primero
            }
            // Verificar si contiene "certificación"
            elseif (str_contains($nombreLower, 'certificación') || str_contains($nombreLower, 'certificacion')) {
                return 3; // Último
            }
            return 2; // En medio
        };

        // Ordenar cuotas según el criterio
        return $cuotas->sort(function ($a, $b) use ($getOrdenCuota) {
            $ordenA = $getOrdenCuota($a->nombre);
            $ordenB = $getOrdenCuota($b->nombre);

            // Primero por orden (matricula -> otras -> certificación)
            if ($ordenA != $ordenB) {
                return $ordenA - $ordenB;
            }

            // Luego por número de cuota
            return $a->n_cuota - $b->n_cuota;
        });
    }

    /**
     * Registrar un pago para una cuota
     */
    public function registrarPago(Request $request, $id)
    {
        try {
            // Validación base
            $request->validate([
                'cuota_id' => 'required|exists:cuotas,id',
                'monto_pago' => 'required|numeric|min:0.01',
                'descuento' => 'nullable|numeric|min:0',
                'tipo_pago' => 'required|in:Efectivo,Transferencia,Depósito,Tarjeta',
                'fecha_pago' => 'required|date',
                'observaciones' => 'nullable|string|max:500',
            ]);

            // Obtener el usuario autenticado
            $user = auth()->user();

            // Obtener el trabajadore_cargo_id a través de la cadena de relaciones
            $trabajadoreCargoId = $this->obtenerTrabajadorCargoId($user);

            if (!$trabajadoreCargoId) {
                return response()->json([
                    'success' => false,
                    'msg' => 'No se pudo identificar al trabajador responsable. Verifique que el usuario esté asignado como trabajador con un cargo principal y vigente.'
                ], 422);
            }

            // Validaciones condicionales según tipo de pago
            if ($request->tipo_pago == 'Efectivo') {
                $request->validate([
                    'caja_id' => 'required|exists:cajas,id',
                ]);
            } else {
                $request->validate([
                    'cuenta_bancaria_id' => 'required|exists:cuentas_bancarias,id',
                    'n_comprobante' => 'required|string|max:100',
                ]);
            }

            // Obtener la cuota
            $cuota = Cuota::findOrFail($request->cuota_id);

            // Verificar que la cuota pertenezca al estudiante
            $estudiante = Estudiante::findOrFail($id);
            if ($cuota->inscripcion->estudiante_id != $estudiante->id) {
                return response()->json([
                    'success' => false,
                    'msg' => 'La cuota no pertenece a este estudiante.'
                ], 403);
            }

            // Verificar que el monto no exceda el pendiente
            $saldoPendiente = $cuota->pago_pendiente_bs;
            if ($request->monto_pago > $saldoPendiente) {
                return response()->json([
                    'success' => false,
                    'msg' => 'El monto a pagar excede el saldo pendiente de la cuota.'
                ], 422);
            }

            DB::beginTransaction();

            // Generar número de recibo incremental
            $ultimoRecibo = Pago::orderBy('id', 'desc')->first();
            $numeroRecibo = 'UNIP-000000001';
            if ($ultimoRecibo && $ultimoRecibo->recibo) {
                $numero = intval(substr($ultimoRecibo->recibo, 4)) + 1;
                $numeroRecibo = 'UNIP-' . str_pad($numero, 9, '0', STR_PAD_LEFT);
            }

            // Preparar datos del pago según tipo
            $datosPago = [
                'recibo' => $numeroRecibo,
                'pago_bs' => $request->monto_pago,
                'descuento_bs' => $request->descuento ?? 0,
                'fecha_pago' => $request->fecha_pago,
                'tipo_pago' => strtolower($request->tipo_pago),
                'estado' => 'registrado',
                'trabajadore_cargo_id' => $trabajadoreCargoId,
            ];

            // Asignar caja o cuenta según tipo de pago
            if ($request->tipo_pago == 'Efectivo') {
                $datosPago['caja_id'] = $request->caja_id;
                $datosPago['cuenta_bancaria_id'] = null;

                // Para efectivo, usar un número de comprobante interno
                $datosPago['n_comprobante'] = 'EF-' . str_pad(rand(1000, 9999), 6, '0', STR_PAD_LEFT);
            } else {
                $datosPago['cuenta_bancaria_id'] = $request->cuenta_bancaria_id;
                $datosPago['n_comprobante'] = $request->n_comprobante;
                $datosPago['caja_id'] = null;
            }

            // 1. Crear el pago primero para obtener su ID
            $pago = Pago::create($datosPago);

            // 2. Registrar movimiento según tipo de pago
            if ($request->tipo_pago == 'Efectivo') {
                // Registrar movimiento en caja
                $caja = Caja::findOrFail($request->caja_id);
                $saldoAnterior = $caja->saldo_actual;
                $saldoPosterior = $saldoAnterior + $request->monto_pago;

                MovimientosCaja::create([
                    'caja_id' => $request->caja_id,
                    'tipo_movimiento' => 'ingreso',
                    'monto' => $request->monto_pago,
                    'saldo_anterior' => $saldoAnterior,
                    'saldo_posterior' => $saldoPosterior,
                    'descripcion' => "Pago en efectivo recibo #{$numeroRecibo}",
                    'referencia_id' => $pago->id,
                    'referencia_type' => Pago::class,
                    'trabajadore_cargo_id' => $trabajadoreCargoId
                ]);

                // Actualizar saldo de caja
                $caja->saldo_actual = $saldoPosterior;
                $caja->save();
            } else {
                // Registrar movimiento bancario
                $cuenta = CuentasBancarias::findOrFail($request->cuenta_bancaria_id);
                $saldoAnterior = $cuenta->saldo_actual;
                $saldoPosterior = $saldoAnterior + $request->monto_pago;

                MovimientosBancarios::create([
                    'cuenta_bancaria_id' => $request->cuenta_bancaria_id,
                    'tipo_movimiento' => 'pago',
                    'monto' => $request->monto_pago,
                    'saldo_anterior' => $saldoAnterior,
                    'saldo_posterior' => $saldoPosterior,
                    'descripcion' => "Pago recibo #{$numeroRecibo}",
                    'referencia_id' => $pago->id,
                    'referencia_type' => Pago::class,
                    'trabajadore_cargo_id' => $trabajadoreCargoId
                ]);

                // Actualizar saldo de cuenta bancaria
                $cuenta->saldo_actual = $saldoPosterior;
                $cuenta->save();
            }

            // 3. Crear el detalle del pago
            Detalle::create([
                'pago_id' => $pago->id,
                'pago_bs' => $request->monto_pago,
                'tipo_pago' => $request->tipo_pago,
            ]);

            // 4. Registrar el pago en la cuota
            PagosCuota::create([
                'cuota_id' => $cuota->id,
                'pago_id' => $pago->id,
                'pago_bs' => $request->monto_pago,
            ]);

            // 5. Actualizar el saldo pendiente de la cuota
            $cuota->pago_pendiente_bs = $cuota->pago_pendiente_bs - $request->monto_pago;

            // Si el saldo pendiente es 0, marcar como pagada
            if ($cuota->pago_pendiente_bs <= 0) {
                $cuota->pago_terminado = 'si';
            }

            $cuota->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Pago registrado correctamente.',
                'recibo' => $numeroRecibo,
                'pdf_url' => route('admin.estudiantes.descargar-recibo', $pago->id),
                'pago_id' => $pago->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar pago: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al registrar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener el trabajadore_cargo_id del usuario autenticado
     * Navega a través de la cadena: User -> Persona -> Trabajadore -> TrabajadoresCargo
     */
    private function obtenerTrabajadorCargoId($user)
    {
        try {
            // Consulta directa para obtener el trabajadore_cargo_id
            $trabajadorCargoId = DB::table('users')
                ->join('personas', 'users.persona_id', '=', 'personas.id')
                ->join('trabajadores', 'personas.id', '=', 'trabajadores.persona_id')
                ->join('trabajadores_cargos', 'trabajadores.id', '=', 'trabajadores_cargos.trabajadore_id')
                ->where('users.id', $user->id)
                ->where('trabajadores_cargos.principal', 1)
                ->where('trabajadores_cargos.estado', 'Vigente')
                ->value('trabajadores_cargos.id');

            if (!$trabajadorCargoId) {
                Log::warning('No se encontró trabajadore_cargo_id para el usuario', [
                    'user_id' => $user->id,
                    'persona_id' => $user->persona_id
                ]);
                return null;
            }

            return $trabajadorCargoId;
        } catch (\Exception $e) {
            Log::error('Error al obtener trabajadore_cargo_id: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Descargar recibo en PDF
     */
    public function descargarRecibo($id)
    {
        $pago = Pago::with([
            'pagos_cuotas.cuota.inscripcion.ofertaAcademica.programa',
            'pagos_cuotas.cuota.inscripcion.ofertaAcademica.sucursal',
            'pagos_cuotas.cuota.inscripcion.estudiante.persona',
            'detalles',
        ])->findOrFail($id);

        $primerPagoCuota = $pago->pagos_cuotas->first();
        $cuota       = $primerPagoCuota ? $primerPagoCuota->cuota : null;
        $estudiante  = $cuota ? $cuota->inscripcion->estudiante : null;
        $pagosCuotas = $pago->pagos_cuotas;

        // Obtener datos del cobrador desde trabajadore_cargo_id del pago
        $cobrador = null;
        if ($pago->trabajadore_cargo_id) {
            $cobrador = DB::table('trabajadores_cargos')
                ->join('trabajadores', 'trabajadores_cargos.trabajadore_id', '=', 'trabajadores.id')
                ->join('personas', 'trabajadores.persona_id', '=', 'personas.id')
                ->join('cargos', 'trabajadores_cargos.cargo_id', '=', 'cargos.id')
                ->where('trabajadores_cargos.id', $pago->trabajadore_cargo_id)
                ->select(
                    'personas.nombres',
                    'personas.apellido_paterno',
                    'personas.apellido_materno',
                    'cargos.nombre as cargo'
                )
                ->first();
        }

        $pdf = PDF::loadView('admin.estudiantes.recibo-pago', compact('pago', 'cuota', 'estudiante', 'pagosCuotas', 'cobrador'));
        return $pdf->download('recibo-' . $pago->recibo . '.pdf');
    }

    /**
     * Obtener cuotas pendientes de un estudiante agrupadas por programa
     */
    public function cuotasPendientesEstudiante($id)
    {
        try {
            $estudiante = Estudiante::with([
                'inscripciones.ofertaAcademica.programa',
                'inscripciones.cuotas',
            ])->findOrFail($id);

            $tipoLabel = function ($nombre) {
                $n = mb_strtolower($nombre);
                if (str_contains($n, 'matr'))  return 'Matrícula';
                if (str_contains($n, 'coleg'))  return 'Colegiatura';
                if (str_contains($n, 'certif')) return 'Certificación';
                return 'Otros';
            };

            $tipoOrden = ['Matrícula' => 1, 'Colegiatura' => 2, 'Certificación' => 3, 'Otros' => 4];

            $programas = [];
            foreach ($estudiante->inscripciones as $inscripcion) {
                $cuotas = $inscripcion->cuotas
                    ->where('pago_pendiente_bs', '>', 0)
                    ->sortBy([
                        fn($a, $b) => ($tipoOrden[$tipoLabel($a->nombre)] ?? 4) <=> ($tipoOrden[$tipoLabel($b->nombre)] ?? 4),
                        fn($a, $b) => $a->n_cuota <=> $b->n_cuota,
                    ])
                    ->values()
                    ->map(fn($c) => [
                        'id'           => $c->id,
                        'nombre'       => $c->nombre,
                        'n_cuota'      => $c->n_cuota,
                        'tipo'         => $tipoLabel($c->nombre),
                        'pendiente_bs' => (float) $c->pago_pendiente_bs,
                        'total_bs'     => (float) $c->pago_total_bs,
                    ]);

                if ($cuotas->isNotEmpty()) {
                    $programas[] = [
                        'inscripcion_id' => $inscripcion->id,
                        'programa'       => $inscripcion->ofertaAcademica->programa->nombre ?? 'N/A',
                        'cuotas'         => $cuotas,
                    ];
                }
            }

            return response()->json(['success' => true, 'programas' => $programas]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Registrar pago para múltiples cuotas (usado desde contabilidad)
     */
    public function registrarPagoMultiple(Request $request, $estudianteId)
    {
        try {
            $request->validate([
                'cuota_ids'          => 'required|array|min:1',
                'cuota_ids.*'        => 'exists:cuotas,id',
                'monto_pago'         => 'required|numeric|min:0.01',
                'descuento'          => 'nullable|numeric|min:0',
                'tipo_pago'          => 'required|in:Efectivo,Transferencia,Depósito,Tarjeta',
                'fecha_pago'         => 'required|date',
                'observaciones'      => 'nullable|string|max:500',
            ]);

            $trabajadoreCargoId = $this->obtenerTrabajadorCargoId(auth()->user());
            if (!$trabajadoreCargoId) {
                return response()->json([
                    'success' => false,
                    'msg' => 'No se pudo identificar al trabajador responsable.'
                ], 422);
            }

            if ($request->tipo_pago === 'Efectivo') {
                $request->validate(['caja_id' => 'required|exists:cajas,id']);
            } else {
                $request->validate([
                    'cuenta_bancaria_id' => 'required|exists:cuentas_bancarias,id',
                    'n_comprobante'      => 'required|string|max:100',
                ]);
            }

            $estudiante = Estudiante::findOrFail($estudianteId);
            $cuotas = Cuota::whereIn('id', $request->cuota_ids)
                ->whereHas('inscripcion', fn($q) => $q->where('estudiante_id', $estudiante->id))
                ->get();

            if ($cuotas->count() !== count($request->cuota_ids)) {
                return response()->json(['success' => false, 'msg' => 'Alguna cuota no pertenece a este estudiante.'], 422);
            }

            $neto = $request->monto_pago - ($request->descuento ?? 0);
            if ($neto <= 0) {
                return response()->json(['success' => false, 'msg' => 'El monto neto debe ser mayor a cero.'], 422);
            }

            DB::beginTransaction();

            // Generar recibo
            $ultimoRecibo = Pago::orderBy('id', 'desc')->first();
            $lastNum      = $ultimoRecibo ? (int) preg_replace('/\D/', '', $ultimoRecibo->recibo) : 0;
            $numeroRecibo = 'UNIP-' . str_pad($lastNum + 1, 9, '0', STR_PAD_LEFT);

            $datosPago = [
                'recibo'              => $numeroRecibo,
                'pago_bs'             => $request->monto_pago,
                'descuento_bs'        => $request->descuento ?? 0,
                'fecha_pago'          => $request->fecha_pago,
                'tipo_pago'           => $request->tipo_pago,
                'estado'              => 'registrado',
                'trabajadore_cargo_id' => $trabajadoreCargoId,
                'n_comprobante'       => $request->n_comprobante ?? null,
            ];

            if ($request->tipo_pago === 'Efectivo') {
                $datosPago['caja_id'] = $request->caja_id;
                $datosPago['n_comprobante'] = 'EF-' . str_pad(rand(1000, 9999), 6, '0', STR_PAD_LEFT);
            } else {
                $datosPago['cuenta_bancaria_id'] = $request->cuenta_bancaria_id;
            }

            $pago = Pago::create($datosPago);

            // Registrar movimiento en caja o banco
            if ($request->tipo_pago === 'Efectivo') {
                $caja = Caja::findOrFail($request->caja_id);
                $saldoAnt = $caja->saldo_actual;
                MovimientosCaja::create([
                    'caja_id'              => $request->caja_id,
                    'tipo_movimiento'      => 'ingreso',
                    'monto'                => $request->monto_pago,
                    'saldo_anterior'       => $saldoAnt,
                    'saldo_posterior'      => $saldoAnt + $request->monto_pago,
                    'descripcion'          => "Pago múltiple recibo #{$numeroRecibo}",
                    'referencia_id'        => $pago->id,
                    'referencia_type'      => Pago::class,
                    'trabajadore_cargo_id' => $trabajadoreCargoId,
                ]);
                $caja->saldo_actual = $saldoAnt + $request->monto_pago;
                $caja->save();
            } else {
                $cuenta = CuentasBancarias::findOrFail($request->cuenta_bancaria_id);
                $saldoAnt = $cuenta->saldo_actual;
                MovimientosBancarios::create([
                    'cuenta_bancaria_id'  => $request->cuenta_bancaria_id,
                    'tipo_movimiento'     => 'pago',
                    'monto'               => $request->monto_pago,
                    'saldo_anterior'      => $saldoAnt,
                    'saldo_posterior'     => $saldoAnt + $request->monto_pago,
                    'descripcion'         => "Pago múltiple recibo #{$numeroRecibo}",
                    'referencia_id'       => $pago->id,
                    'referencia_type'     => Pago::class,
                    'trabajadore_cargo_id' => $trabajadoreCargoId,
                ]);
                $cuenta->saldo_actual = $saldoAnt + $request->monto_pago;
                $cuenta->save();
            }

            // Crear detalle
            $detalle = new Detalle();
            $detalle->pago_id  = $pago->id;
            $detalle->pago_bs  = $neto;
            $detalle->tipo_pago = $request->tipo_pago;
            $detalle->save();

            // Distribuir monto entre cuotas seleccionadas
            $montoRestante = $neto;
            foreach ($cuotas as $cuota) {
                if ($montoRestante <= 0) break;
                $montoCuota = min($cuota->pago_pendiente_bs, $montoRestante);
                if ($montoCuota <= 0) continue;

                PagosCuota::create([
                    'cuota_id' => $cuota->id,
                    'pago_id'  => $pago->id,
                    'pago_bs'  => $montoCuota,
                ]);

                $cuota->pago_pendiente_bs -= $montoCuota;
                if ($cuota->pago_pendiente_bs <= 0) {
                    $cuota->pago_pendiente_bs = 0;
                    $cuota->pago_terminado    = 'si';
                }
                $cuota->save();
                $montoRestante -= $montoCuota;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'msg'     => 'Pago registrado correctamente.',
                'recibo'  => $numeroRecibo,
                'pdf_url' => route('admin.estudiantes.descargar-recibo', $pago->id),
                'pago_id' => $pago->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar pago múltiple: ' . $e->getMessage());
            return response()->json(['success' => false, 'msg' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtener datos de una cuota para el pago
     */
    public function obtenerDatosCuota($id, $cuotaId)
    {
        try {
            $estudiante = Estudiante::findOrFail($id);
            $cuota = Cuota::with(['inscripcion.ofertaAcademica.programa'])->findOrFail($cuotaId);

            // Verificar que la cuota pertenezca al estudiante
            if ($cuota->inscripcion->estudiante_id != $estudiante->id) {
                return response()->json([
                    'success' => false,
                    'msg' => 'La cuota no pertenece a este estudiante.'
                ], 403);
            }

            // Asegurarse de que los valores sean numéricos
            $pago_total_bs = (float) $cuota->pago_total_bs;
            $pago_pendiente_bs = (float) $cuota->pago_pendiente_bs;
            $saldo_pagado = $pago_total_bs - $pago_pendiente_bs;

            return response()->json([
                'success' => true,
                'cuota' => [
                    'id' => $cuota->id,
                    'nombre' => $cuota->nombre,
                    'n_cuota' => $cuota->n_cuota,
                    'pago_total_bs' => $pago_total_bs,
                    'pago_pendiente_bs' => $pago_pendiente_bs,
                    'saldo_pagado' => $saldo_pagado,
                    'programa' => $cuota->inscripcion->ofertaAcademica->programa->nombre ?? 'N/A',
                    'inscripcion_id' => $cuota->inscripcion->id,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener datos de cuota: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al obtener datos de la cuota.'
            ], 500);
        }
    }

    // Agrega este método al controlador
    public function editar($id)
    {
        try {
            $estudiante = Estudiante::with(['persona.ciudad.departamento', 'persona.estudios'])->findOrFail($id);

            $departamentos = Departamento::all();
            $ciudades = Ciudade::with('departamento')->get();
            $grados = GradosAcademico::all();
            $profesiones = Profesione::all();
            $universidades = Universidade::all();

            return view('admin.estudiantes.editar', compact(
                'estudiante',
                'departamentos',
                'ciudades',
                'grados',
                'profesiones',
                'universidades'
            ));
        } catch (\Exception $e) {
            Log::error('Error al cargar estudiante para edición: ' . $e->getMessage());
            return redirect()->route('admin.estudiantes.listar')
                ->with('error', 'Error al cargar el estudiante: ' . $e->getMessage());
        }
    }

    public function actualizar(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:estudiantes,id',
                'nombres' => 'required|string|max:255',
                'expedido' => 'nullable|in:Lp,Or,Pt,Cb,Ch,Tj,Be,Sc,Pn',
                'apellido_paterno' => 'nullable|string|max:255',
                'apellido_materno' => 'nullable|string|max:255',
                'sexo' => 'required|in:Hombre,Mujer',
                'estado_civil' => 'required|in:Soltero(a),Casado(a),Divorciado(a),Viudo(a)',
                'fecha_nacimiento' => 'nullable|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
                'correo' => 'required|email',
                'celular' => 'required|numeric',
                'telefono' => 'nullable|numeric',
                'ciudade_id' => 'required|exists:ciudades,id',
            ]);

            $estudiante = Estudiante::findOrFail($request->id);
            $persona = $estudiante->persona;

            // Verificar si el correo ya existe para otra persona
            if ($request->correo !== $persona->correo) {
                $existeCorreo = Persona::where('correo', $request->correo)
                    ->where('id', '!=', $persona->id)
                    ->exists();

                if ($existeCorreo) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'El correo electrónico ya está registrado por otra persona.'
                    ], 422);
                }
            }

            // Actualizar persona
            $persona->update($request->only([
                'nombres',
                'expedido',
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

            // Actualizar estudios
            if ($request->has('estudios') && is_array($request->estudios)) {
                // Eliminar estudios existentes
                $persona->estudios()->delete();

                // Crear nuevos estudios
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
                'msg' => 'Estudiante actualizado correctamente.',
                'redirect' => route('admin.estudiantes.listar')
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar estudiante: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verificarCarnet(Request $request)
    {
        \Log::info('Verificando carnet:', ['carnet' => $request->carnet]);
        $carnet = $request->carnet;

        $persona = Persona::where('carnet', $carnet)->first();

        if (!$persona) {
            \Log::warning('Persona no encontrada con carnet:', ['carnet' => $carnet]);
            return response()->json([
                'exists' => false,
                'is_student' => false,
                'message' => 'La persona no está registrada.'
            ]);
        }

        \Log::info('Persona encontrada:', ['persona_id' => $persona->id]);

        $estudiante = Estudiante::where('persona_id', $persona->id)->first();

        if ($estudiante) {
            \Log::info('Persona es estudiante:', ['estudiante_id' => $estudiante->id]);
            return response()->json([
                'exists' => true,
                'is_student' => true,
                'message' => 'Ya es estudiante la persona.',
                'persona' => [
                    'id' => $persona->id,
                    'nombre_completo' => trim("{$persona->apellido_paterno} {$persona->apellido_materno}, {$persona->nombres}"),
                    'carnet' => $persona->carnet,
                ],
                'estudiante_id' => $estudiante->id
            ]);
        }

        \Log::info('Persona NO es estudiante.');
        return response()->json([
            'exists' => true,
            'is_student' => false,
            'message' => 'La persona está registrada pero no es estudiante.',
            'persona' => [
                'id' => $persona->id,
                'nombre_completo' => trim("{$persona->apellido_paterno} {$persona->apellido_materno}, {$persona->nombres}"),
                'carnet' => $persona->carnet,
            ]
        ]);
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'persona_id' => 'required|exists:personas,id',
        ]);

        // Verificar que no esté ya registrado como estudiante
        if (Estudiante::where('persona_id', $request->persona_id)->exists()) {
            return response()->json([
                'success' => false,
                'msg' => 'Esta persona ya está registrada como estudiante.'
            ], 422);
        }

        Estudiante::create([
            'persona_id' => $request->persona_id,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Estudiante registrado correctamente.'
        ]);
    }

    public function eliminar(Request $request)
    {
        $request->validate(['id' => 'required|exists:estudiantes,id']);

        Estudiante::where('id', $request->id)->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Estudiante eliminado correctamente.'
        ]);
    }



    public function registrarPersonaYEstudiante(Request $request)
    {
        $request->validate([
            'carnet' => 'required|unique:personas,carnet',
            'nombres' => 'required|string|max:255',
            'expedido' => 'nullable|in:Lp,Or,Pt,Cb,Ch,Tj,Be,Sc,Pn',
            'apellido_paterno' => 'nullable|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'sexo' => 'required|in:Hombre,Mujer',
            'estado_civil' => 'required|in:Soltero(a),Casado(a),Divorciado(a),Viudo(a)',
            'fecha_nacimiento' => 'nullable|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            'correo' => 'required|email|unique:personas,correo',
            'celular' => 'required|numeric',
            'telefono' => 'nullable|numeric',
            'ciudade_id' => 'required|exists:ciudades,id',
        ]);

        if (empty($request->apellido_paterno) && empty($request->apellido_materno)) {
            return response()->json([
                'success' => false,
                'errors' => ['apellidos' => ['Debe ingresar al menos un apellido.']]
            ], 422);
        }

        try {
            // Crear persona
            $persona = Persona::create($request->only([
                'carnet',
                'nombres',
                'expedido',
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

            // Crear estudiante
            $estudiante = Estudiante::create(['persona_id' => $persona->id]);

            // Guardar estudios si existen
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
                'msg' => 'Persona registrada y asignada como estudiante correctamente.',
                'student_id' => $estudiante->id,
                'persona_id' => $persona->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al registrar persona y estudiante: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al registrar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subir carnet de identidad (renombrado)
     */
    public function subirDocumentoCarnet(Request $request, $id)
    {
        \Log::info('========= INICIANDO SUBIDA DE CARNET =========');
        \Log::info('Estudiante ID: ' . $id);
        \Log::info('Archivo recibido: ' . ($request->hasFile('carnet_pdf') ? 'SÍ' : 'NO'));

        try {
            $validator = Validator::make($request->all(), [
                'carnet_pdf' => 'required|mimes:pdf|max:2048',
            ]);

            if ($validator->fails()) {
                \Log::error('Validación fallida: ' . json_encode($validator->errors()));
                return response()->json([
                    'success' => false,
                    'msg' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $estudiante = Estudiante::findOrFail($id);
            $persona = $estudiante->persona;

            \Log::info('Persona encontrada: ' . $persona->id . ' - ' . $persona->carnet);

            // Verificar el archivo
            $file = $request->file('carnet_pdf');
            \Log::info('Archivo info: ' . $file->getClientOriginalName() . ', Tamaño: ' . $file->getSize());

            // Verificar si la carpeta existe
            $directory = storage_path('app/public/upload/documentos/carnet');
            \Log::info('Directorio destino: ' . $directory);
            \Log::info('Directorio existe: ' . (file_exists($directory) ? 'SÍ' : 'NO'));

            if (!file_exists($directory)) {
                \Log::info('Creando directorio...');
                mkdir($directory, 0755, true);
            }

            // Probar diferentes métodos de guardado
            $fileName = 'carnet_' . $persona->carnet . '_' . time() . '.pdf';

            // Método 1: Usando storeAs
            $path = $file->storeAs('public/upload/documentos/carnet', $fileName);
            \Log::info('Archivo guardado (storeAs): ' . $path);

            // Verificar si el archivo existe físicamente
            $fullPath = storage_path('app/' . $path);
            \Log::info('Ruta completa: ' . $fullPath);
            \Log::info('Archivo existe físicamente: ' . (file_exists($fullPath) ? 'SÍ' : 'NO'));

            // Guardar en BD
            $persona->fotografia_carnet = 'upload/documentos/carnet/' . $fileName;
            $persona->carnet_verificado = 0;
            $persona->save();

            \Log::info('Registro guardado en BD: ' . $persona->fotografia_carnet);

            return response()->json([
                'success' => true,
                'msg' => 'Carnet subido correctamente',
                'file_path' => asset('storage/upload/documentos/carnet/' . $fileName)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error completo al subir carnet: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'msg' => 'Error al subir el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subir certificado de nacimiento (renombrado)
     */
    public function subirDocumentoCertificadoNacimiento(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'certificado_nacimiento_pdf' => 'required|mimes:pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $estudiante = Estudiante::findOrFail($id);
            $persona = $estudiante->persona;

            // Eliminar archivo anterior si existe
            if ($persona->fotografia_certificado_nacimiento && Storage::exists('public/' . $persona->fotografia_certificado_nacimiento)) {
                Storage::delete('public/' . $persona->fotografia_certificado_nacimiento);
            }

            // Subir nuevo archivo
            $file = $request->file('certificado_nacimiento_pdf');
            $fileName = 'certificado_nacimiento_' . $persona->carnet . '_' . time() . '.pdf';
            $path = $file->storeAs('public/upload/documentos/certificado_nacimiento', $fileName);

            $persona->fotografia_certificado_nacimiento = 'upload/documentos/certificado_nacimiento/' . $fileName;
            $persona->certificado_nacimiento_verificado = 0; // Resetear verificación
            $persona->save();

            return response()->json([
                'success' => true,
                'msg' => 'Certificado de nacimiento subido correctamente',
                'file_path' => asset('storage/upload/documentos/certificado_nacimiento/' . $fileName)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al subir certificado de nacimiento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al subir el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subir título académico (renombrado)
     */
    public function subirDocumentoTituloAcademico(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titulo_academico_pdf' => 'required|mimes:pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $estudiante = Estudiante::findOrFail($id);
            $persona = $estudiante->persona;

            // Obtener estudio principal
            $estudioPrincipal = $persona->estudios->where('principal', 1)->first();

            if (!$estudioPrincipal) {
                return response()->json([
                    'success' => false,
                    'msg' => 'No existe un estudio marcado como principal'
                ], 404);
            }

            // Eliminar archivo anterior si existe
            if ($estudioPrincipal->documento_academico && Storage::exists('public/' . $estudioPrincipal->documento_academico)) {
                Storage::delete('public/' . $estudioPrincipal->documento_academico);
            }

            // Subir nuevo archivo
            $file = $request->file('titulo_academico_pdf');
            $fileName = 'titulo_academico_' . $persona->carnet . '_' . time() . '.pdf';
            $path = $file->storeAs('public/upload/documentos/titulo_academico', $fileName);

            $estudioPrincipal->documento_academico = 'upload/documentos/titulo_academico/' . $fileName;
            $estudioPrincipal->documento_academico_verificado = 0; // Resetear verificación
            $estudioPrincipal->save();

            return response()->json([
                'success' => true,
                'msg' => 'Título académico subido correctamente',
                'file_path' => asset('storage/upload/documentos/titulo_academico/' . $fileName)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al subir título académico: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al subir el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Subir título de provisión nacional (renombrado)
     */
    public function subirDocumentoProvisionNacional(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'provision_nacional_pdf' => 'required|mimes:pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $estudiante = Estudiante::findOrFail($id);
            $persona = $estudiante->persona;

            // Obtener estudio principal
            $estudioPrincipal = $persona->estudios->where('principal', 1)->first();

            if (!$estudioPrincipal) {
                return response()->json([
                    'success' => false,
                    'msg' => 'No existe un estudio marcado como principal'
                ], 404);
            }

            // Eliminar archivo anterior si existe
            if ($estudioPrincipal->documento_provision_nacional && Storage::exists('public/' . $estudioPrincipal->documento_provision_nacional)) {
                Storage::delete('public/' . $estudioPrincipal->documento_provision_nacional);
            }

            // Subir nuevo archivo
            $file = $request->file('provision_nacional_pdf');
            $fileName = 'provision_nacional_' . $persona->carnet . '_' . time() . '.pdf';
            $path = $file->storeAs('public/upload/documentos/titulo_provision_nacional', $fileName);

            $estudioPrincipal->documento_provision_nacional = 'upload/documentos/titulo_provision_nacional/' . $fileName;
            $estudioPrincipal->documento_provision_verificado = 0; // Resetear verificación
            $estudioPrincipal->save();

            return response()->json([
                'success' => true,
                'msg' => 'Título de provisión nacional subido correctamente',
                'file_path' => asset('storage/upload/documentos/titulo_provision_nacional/' . $fileName)
            ]);
        } catch (\Exception $e) {
            Log::error('Error al subir provisión nacional: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al subir el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar documento carnet
     */
    public function verificarDocumentoCarnet(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'verificado' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $estudiante = Estudiante::findOrFail($id);
            $persona = $estudiante->persona;

            $persona->carnet_verificado = $request->verificado;
            $persona->save();

            $estado = $request->verificado ? 'verificado' : 'no verificado';

            return response()->json([
                'success' => true,
                'msg' => 'Carnet marcado como ' . $estado,
                'verificado' => $persona->carnet_verificado
            ]);
        } catch (\Exception $e) {
            Log::error('Error al verificar carnet: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al verificar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar certificado de nacimiento
     */
    public function verificarDocumentoCertificadoNacimiento(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'verificado' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $estudiante = Estudiante::findOrFail($id);
            $persona = $estudiante->persona;

            $persona->certificado_nacimiento_verificado = $request->verificado;
            $persona->save();

            $estado = $request->verificado ? 'verificado' : 'no verificado';

            return response()->json([
                'success' => true,
                'msg' => 'Certificado de nacimiento marcado como ' . $estado,
                'verificado' => $persona->certificado_nacimiento_verificado
            ]);
        } catch (\Exception $e) {
            Log::error('Error al verificar certificado de nacimiento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al verificar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar documento académico
     */
    public function verificarDocumentoAcademico(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'verificado' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $estudiante = Estudiante::findOrFail($id);
            $persona = $estudiante->persona;
            $estudioPrincipal = $persona->estudios->where('principal', 1)->first();

            if (!$estudioPrincipal) {
                return response()->json([
                    'success' => false,
                    'msg' => 'No existe un estudio marcado como principal'
                ], 404);
            }

            $estudioPrincipal->documento_academico_verificado = $request->verificado;
            $estudioPrincipal->save();

            $estado = $request->verificado ? 'verificado' : 'no verificado';

            return response()->json([
                'success' => true,
                'msg' => 'Documento académico marcado como ' . $estado,
                'verificado' => $estudioPrincipal->documento_academico_verificado
            ]);
        } catch (\Exception $e) {
            Log::error('Error al verificar documento académico: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al verificar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar provisión nacional
     */
    public function verificarDocumentoProvisionNacional(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'verificado' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Error de validación: ' . $validator->errors()->first()
                ], 422);
            }

            $estudiante = Estudiante::findOrFail($id);
            $persona = $estudiante->persona;
            $estudioPrincipal = $persona->estudios->where('principal', 1)->first();

            if (!$estudioPrincipal) {
                return response()->json([
                    'success' => false,
                    'msg' => 'No existe un estudio marcado como principal'
                ], 404);
            }

            $estudioPrincipal->documento_provision_verificado = $request->verificado;
            $estudioPrincipal->save();

            $estado = $request->verificado ? 'verificado' : 'no verificado';

            return response()->json([
                'success' => true,
                'msg' => 'Provisión nacional marcada como ' . $estado,
                'verificado' => $estudioPrincipal->documento_provision_verificado
            ]);
        } catch (\Exception $e) {
            Log::error('Error al verificar provisión nacional: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'msg' => 'Error al verificar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Historial de recibos con filtros
     */
    /**
     * Historial de recibos con filtros
     */
    public function historialRecibos(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $tipoPago = $request->get('tipo_pago');
        $recibo = $request->get('recibo');
        $carnet = $request->get('carnet');

        $query = Pago::with([
            'pagos_cuotas.cuota.inscripcion.estudiante.persona',
            'pagos_cuotas.cuota.inscripcion.ofertaAcademica.programa',
            'detalles'
        ])->orderBy('fecha_pago', 'desc')->orderBy('id', 'desc');

        // Aplicar filtros
        if ($fechaInicio) {
            $query->whereDate('fecha_pago', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->whereDate('fecha_pago', '<=', $fechaFin);
        }
        if ($tipoPago && $tipoPago != 'Todos') {
            $query->where('tipo_pago', $tipoPago);
        }
        if ($recibo) {
            $query->where('recibo', 'like', "%{$recibo}%");
        }
        if ($carnet) {
            $query->whereHas('pagos_cuotas.cuota.inscripcion.estudiante.persona', function ($q) use ($carnet) {
                $q->where('carnet', 'like', "%{$carnet}%");
            });
        }

        // Estadísticas sobre el total filtrado (antes de paginar)
        $estadisticas = [
            'total_recibos'      => (clone $query)->count(),
            'total_monto'        => (clone $query)->sum('pago_bs'),
            'total_efectivo'     => (clone $query)->where('tipo_pago', 'Efectivo')->sum('pago_bs'),
            'total_transferencia'=> (clone $query)->where('tipo_pago', 'Transferencia')->sum('pago_bs'),
            'total_deposito'     => (clone $query)->where('tipo_pago', 'Depósito')->sum('pago_bs'),
            'total_tarjeta'      => (clone $query)->where('tipo_pago', 'Tarjeta')->sum('pago_bs'),
        ];

        $perPage = (int) $request->get('per_page', 20);
        if (!in_array($perPage, [10, 20, 50, 100])) $perPage = 20;

        $recibos = $query->paginate($perPage)->appends($request->all());

        if ($request->ajax()) {
            $html = view('admin.recibos.partials.table-body', compact('recibos'))->render();
            $pagination = (string) $recibos->links('pagination::bootstrap-5');

            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'estadisticas' => $estadisticas
            ]);
        }

        return view('admin.recibos.historial', compact('recibos', 'estadisticas'));
    }

    /**
     * Recibos filtrados (AJAX)
     */
    public function recibosFiltrados(Request $request)
    {
        return $this->historialRecibos($request);
    }

    /**
     * Exportar recibos a Excel
     */
    public function exportarRecibos(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $tipoPago = $request->get('tipo_pago');

        $query = Pago::with([
            'pagos_cuotas.cuota.inscripcion.estudiante.persona',
            'pagos_cuotas.cuota.inscripcion.ofertaAcademica.programa'
        ]);

        if ($fechaInicio) {
            $query->whereDate('fecha_pago', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->whereDate('fecha_pago', '<=', $fechaFin);
        }
        if ($tipoPago && $tipoPago != 'Todos') {
            $query->where('tipo_pago', $tipoPago);
        }

        $recibos = $query->orderBy('fecha_pago', 'desc')->get();

        // Crear archivo Excel
        $filename = 'recibos_' . date('Y-m-d_His') . '.xlsx';

        // Si tienes instalado Maatwebsite/Excel, usa esto:
        /*
    return Excel::download(new RecibosExport($recibos), $filename);
    */

        // Alternativa: CSV simple
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($recibos) {
            $file = fopen('php://output', 'w');

            // Encabezados
            fputcsv($file, [
                'Recibo',
                'Fecha Pago',
                'Estudiante',
                'Carnet',
                'Programa',
                'Monto (Bs)',
                'Descuento (Bs)',
                'Tipo Pago'
            ]);

            // Datos
            foreach ($recibos as $pago) {
                $estudiante = $pago->pagos_cuotas->first()->cuota->inscripcion->estudiante ?? null;
                $persona = $estudiante->persona ?? null;
                $programa = $pago->pagos_cuotas->first()->cuota->inscripcion->ofertaAcademica->programa ?? null;

                fputcsv($file, [
                    $pago->recibo,
                    $pago->fecha_pago,
                    $persona ? $persona->nombres . ' ' . $persona->apellido_paterno : 'N/A',
                    $persona ? $persona->carnet : 'N/A',
                    $programa ? $programa->nombre : 'N/A',
                    $pago->pago_bs,
                    $pago->descuento_bs,
                    $pago->tipo_pago
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Búsqueda contable por carnet
     */
    public function busquedaContable()
    {
        return view('admin.contabilidad.buscar');
    }

    /**
     * Reporte de cobradores
     */
    public function cobradoresReporte(Request $request)
    {
        $fechaDesde = $request->get('fecha_desde', now()->toDateString());
        $fechaHasta = $request->get('fecha_hasta', now()->toDateString());
        $tipoPago   = $request->get('tipo_pago', '');

        $query = DB::table('pagos')
            ->join('trabajadores_cargos', 'pagos.trabajadore_cargo_id', '=', 'trabajadores_cargos.id')
            ->join('trabajadores', 'trabajadores_cargos.trabajadore_id', '=', 'trabajadores.id')
            ->join('personas', 'trabajadores.persona_id', '=', 'personas.id')
            ->join('cargos', 'trabajadores_cargos.cargo_id', '=', 'cargos.id')
            ->whereBetween(DB::raw('DATE(pagos.fecha_pago)'), [$fechaDesde, $fechaHasta]);

        if ($tipoPago) {
            $query->where('pagos.tipo_pago', $tipoPago);
        }

        $cobradores = (clone $query)
            ->select(
                'trabajadores_cargos.id as tc_id',
                DB::raw("TRIM(CONCAT(personas.nombres, ' ', personas.apellido_paterno, ' ', COALESCE(personas.apellido_materno, ''))) as nombre_completo"),
                'cargos.nombre as cargo',
                DB::raw('COUNT(pagos.id) as total_pagos'),
                DB::raw('SUM(pagos.pago_bs) as total_bs'),
                DB::raw('SUM(CASE WHEN pagos.tipo_pago = "Efectivo"      THEN pagos.pago_bs ELSE 0 END) as efectivo_bs'),
                DB::raw('SUM(CASE WHEN pagos.tipo_pago = "Transferencia" THEN pagos.pago_bs ELSE 0 END) as transferencia_bs'),
                DB::raw('SUM(CASE WHEN pagos.tipo_pago = "Depósito"      THEN pagos.pago_bs ELSE 0 END) as deposito_bs'),
                DB::raw('SUM(CASE WHEN pagos.tipo_pago = "Tarjeta"       THEN pagos.pago_bs ELSE 0 END) as tarjeta_bs')
            )
            ->groupBy('trabajadores_cargos.id', 'nombre_completo', 'cargos.nombre')
            ->orderByDesc('total_bs')
            ->get();

        $totales = [
            'pagos'         => $cobradores->sum('total_pagos'),
            'total'         => $cobradores->sum('total_bs'),
            'efectivo'      => $cobradores->sum('efectivo_bs'),
            'transferencia' => $cobradores->sum('transferencia_bs'),
            'deposito'      => $cobradores->sum('deposito_bs'),
            'tarjeta'       => $cobradores->sum('tarjeta_bs'),
        ];

        return view('admin.contabilidad.cobradores', compact(
            'cobradores', 'totales', 'fechaDesde', 'fechaHasta', 'tipoPago'
        ));
    }

    /**
     * Detalle de pagos de un cobrador (AJAX)
     */
    public function cobradoresDetalle(Request $request, $tcId)
    {
        $fechaDesde = $request->get('fecha_desde', now()->toDateString());
        $fechaHasta = $request->get('fecha_hasta', now()->toDateString());
        $tipoPago   = $request->get('tipo_pago', '');

        $query = DB::table('pagos')
            ->join('pagos_cuotas',       'pagos.id',                        '=', 'pagos_cuotas.pago_id')
            ->join('cuotas',             'pagos_cuotas.cuota_id',           '=', 'cuotas.id')
            ->join('inscripciones',      'cuotas.inscripcione_id',          '=', 'inscripciones.id')
            ->join('estudiantes',        'inscripciones.estudiante_id',     '=', 'estudiantes.id')
            ->join('personas as pe_est', 'estudiantes.persona_id',          '=', 'pe_est.id')
            ->join('ofertas_academicas', 'inscripciones.ofertas_academica_id', '=', 'ofertas_academicas.id')
            ->join('programas',          'ofertas_academicas.programa_id',  '=', 'programas.id')
            ->where('pagos.trabajadore_cargo_id', $tcId)
            ->whereBetween(DB::raw('DATE(pagos.fecha_pago)'), [$fechaDesde, $fechaHasta]);

        if ($tipoPago) {
            $query->where('pagos.tipo_pago', $tipoPago);
        }

        $pagos = $query->select(
                'pagos.id',
                'pagos.recibo',
                'pagos.pago_bs',
                'pagos.descuento_bs',
                'pagos.tipo_pago',
                'pagos.fecha_pago',
                DB::raw("TRIM(CONCAT(pe_est.nombres, ' ', pe_est.apellido_paterno, ' ', COALESCE(pe_est.apellido_materno, ''))) as estudiante"),
                'pe_est.carnet',
                'programas.nombre as programa',
                DB::raw('COUNT(DISTINCT pagos_cuotas.cuota_id) as n_cuotas')
            )
            ->groupBy(
                'pagos.id', 'pagos.recibo', 'pagos.pago_bs', 'pagos.descuento_bs',
                'pagos.tipo_pago', 'pagos.fecha_pago', 'pe_est.nombres',
                'pe_est.apellido_paterno', 'pe_est.apellido_materno',
                'pe_est.carnet', 'programas.nombre'
            )
            ->orderByDesc('pagos.fecha_pago')
            ->get();

        return response()->json(['success' => true, 'pagos' => $pagos]);
    }

    /**
     * Verificar carnet para contabilidad
     */
    public function verificarCarnetContable(Request $request)
    {
        $carnet = $request->carnet;

        // Buscar persona por carnet
        $persona = Persona::where('carnet', $carnet)->first();

        if (!$persona) {
            return response()->json([
                'success' => false,
                'msg' => 'No se encontró ninguna persona con ese carnet.'
            ]);
        }

        // Verificar si es estudiante
        $estudiante = Estudiante::where('persona_id', $persona->id)->first();

        if (!$estudiante) {
            return response()->json([
                'success' => false,
                'msg' => 'La persona encontrada no es un estudiante.'
            ]);
        }

        // Obtener datos del estudiante
        $estudiante->load([
            'persona.ciudad.departamento',
            'persona.estudios.grado_academico',
            'persona.estudios.profesion',
            'persona.estudios.universidad',
            'inscripciones.ofertaAcademica.programa',
            'inscripciones.ofertaAcademica.modalidad',
            'inscripciones.ofertaAcademica.sucursal',
            'inscripciones.planesPago',
            'inscripciones.trabajador_cargo.trabajador.persona',
            'inscripciones.cuotas' => function ($query) {
                $query->orderBy('n_cuota', 'asc');
            },
            'inscripciones.cuotas.pagos_cuotas.pago.detalles'
        ]);

        // Ordenar cuotas
        foreach ($estudiante->inscripciones as $inscripcion) {
            $inscripcion->cuotas_ordenadas = $this->ordenarCuotas($inscripcion->cuotas);
        }

        // Calcular totales
        $totalDeuda = 0;
        $totalPagado = 0;
        $totalProgramas = 0;

        foreach ($estudiante->inscripciones as $inscripcion) {
            $totalProgramas++;
            foreach ($inscripcion->cuotas as $cuota) {
                $totalPagado += ($cuota->pago_total_bs - $cuota->pago_pendiente_bs);
                $totalDeuda += $cuota->pago_pendiente_bs;
            }
        }

        return response()->json([
            'success' => true,
            'estudiante' => [
                'id' => $estudiante->id,
                'nombre_completo' => trim("{$persona->apellido_paterno} {$persona->apellido_materno}, {$persona->nombres}"),
                'carnet' => $persona->carnet,
                'correo' => $persona->correo,
                'celular' => $persona->celular,
                'total_programas' => $totalProgramas,
                'total_pagado' => $totalPagado,
                'total_deuda' => $totalDeuda
            ],
            'redirect' => route('admin.contabilidad.estudiante', $estudiante->id)
        ]);
    }

    /**
     * Detalle del estudiante para contabilidad
     */
    public function detalleContable($id)
    {
        try {
            $estudiante = Estudiante::with([
                'persona.ciudad.departamento',
                'persona.estudios.grado_academico',
                'persona.estudios.profesion',
                'persona.estudios.universidad',
                'inscripciones.ofertaAcademica.programa',
                'inscripciones.ofertaAcademica.modalidad',
                'inscripciones.ofertaAcademica.sucursal',
                'inscripciones.planesPago',
                'inscripciones.trabajador_cargo.trabajador.persona',
                'inscripciones.matriculaciones.modulo.docente.persona',
                'inscripciones.cuotas' => function ($query) {
                    $query->orderBy('n_cuota', 'asc');
                },
                'inscripciones.cuotas.pagos_cuotas.pago.detalles'
            ])->findOrFail($id);

            // Ordenar cuotas para cada inscripción
            foreach ($estudiante->inscripciones as $inscripcion) {
                $inscripcion->cuotas_ordenadas = $this->ordenarCuotas($inscripcion->cuotas);
            }

            // Obtener datos del cobrador (usuario autenticado)
            $cobrador = DB::table('users')
                ->join('personas', 'users.persona_id', '=', 'personas.id')
                ->join('trabajadores', 'personas.id', '=', 'trabajadores.persona_id')
                ->join('trabajadores_cargos', 'trabajadores.id', '=', 'trabajadores_cargos.trabajadore_id')
                ->join('cargos', 'trabajadores_cargos.cargo_id', '=', 'cargos.id')
                ->where('users.id', auth()->id())
                ->where('trabajadores_cargos.principal', 1)
                ->where('trabajadores_cargos.estado', 'Vigente')
                ->select(
                    'personas.nombres',
                    'personas.apellido_paterno',
                    'personas.apellido_materno',
                    'cargos.nombre as cargo',
                    'trabajadores_cargos.id as trabajadore_cargo_id'
                )
                ->first();

            return view('admin.contabilidad.detalle', compact('estudiante', 'cobrador'));
        } catch (\Exception $e) {
            Log::error('Error al cargar estudiante para contabilidad: ' . $e->getMessage());
            return redirect()->route('admin.contabilidad.buscar')
                ->with('error', 'Estudiante no encontrado');
        }
    }
}
