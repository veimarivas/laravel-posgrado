<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PagoRespaldo;
use App\Models\Cuota;
use App\Models\Pago;
use App\Models\PagosCuota;
use App\Models\Detalle;
use App\Models\Caja;
use App\Models\CuentasBancarias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ComprobantesController extends Controller
{
    /**
     * Mostrar listado de comprobantes
     */
    public function index(Request $request)
    {
        return view('admin.comprobantes.index');
    }

    /**
     * Obtener datos para la tabla (AJAX)
     */
    public function datos(Request $request)
    {
        $estado = $request->get('estado', 'pendiente');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $search = $request->get('search');

        $query = PagoRespaldo::with([
            'inscripcion.estudiante.persona',
            'inscripcion.ofertaAcademica.programa',
            'cuotas'
        ])->orderBy('created_at', 'desc');

        if ($estado && $estado != 'todos') {
            $query->where('estado', $estado);
        }

        if ($fechaInicio) {
            $query->whereDate('created_at', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->whereDate('created_at', '<=', $fechaFin);
        }

        if ($search) {
            $query->whereHas('inscripcion.estudiante.persona', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                    ->orWhere('apellido_paterno', 'like', "%{$search}%")
                    ->orWhere('apellido_materno', 'like', "%{$search}%")
                    ->orWhere('carnet', 'like', "%{$search}%");
            })->orWhereHas('inscripcion.ofertaAcademica.programa', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%");
            });
        }

        $comprobantes = $query->paginate(15);

        if ($request->ajax()) {
            $html = view('admin.comprobantes.partials.table-body', compact('comprobantes'))->render();
            $pagination = $comprobantes->links('pagination::bootstrap-5')->render();

            return response()->json([
                'html' => $html,
                'pagination' => $pagination
            ]);
        }

        return view('admin.comprobantes.index', compact('comprobantes'));
    }

    /**
     * Obtener detalle de un comprobante
     */
    public function detalle($id)
    {
        try {
            $comprobante = PagoRespaldo::with([
                'inscripcion.estudiante.persona',
                'inscripcion.ofertaAcademica.programa',
                'inscripcion.ofertaAcademica.sucursal.sede',
                'cuotas'
            ])->findOrFail($id);

            // Obtener también las cuotas pendientes de esta inscripción
            $cuotasPendientes = Cuota::where('inscripcione_id', $comprobante->inscripcion->id)
                ->where('pago_pendiente_bs', '>', 0)
                ->orderBy('n_cuota')
                ->get();

            // Datos del estudiante
            $estudiante = $comprobante->inscripcion->estudiante->persona;



            return response()->json([
                'success' => true,
                'comprobante' => [
                    'id' => $comprobante->id,
                    'archivo' => $comprobante->archivo,
                    'observaciones' => $comprobante->observaciones,
                    'estado' => $comprobante->estado,
                    'inscripcion_id' => $comprobante->inscripcione_id,
                ],
                'estudiante' => [
                    'nombre' => $estudiante->nombres . ' ' . $estudiante->apellido_paterno,
                    'carnet' => $estudiante->carnet,
                    'id' => $comprobante->inscripcion->estudiante_id,
                ],
                'programa' => $comprobante->inscripcion->ofertaAcademica->programa->nombre ?? 'N/A',
                'cuotas' => $comprobante->cuotas->map(function ($cuota) {
                    return [
                        'id' => $cuota->id,
                        'nombre' => $cuota->nombre,
                        'n_cuota' => $cuota->n_cuota,
                        'pago_pendiente_bs' => $cuota->pago_pendiente_bs,
                        'pago_total_bs' => $cuota->pago_total_bs,
                    ];
                }),
                'cuotas_pendientes' => $cuotasPendientes->map(function ($cuota) {
                    return [
                        'id' => $cuota->id,
                        'nombre' => $cuota->nombre,
                        'n_cuota' => $cuota->n_cuota,
                        'pago_pendiente_bs' => $cuota->pago_pendiente_bs,
                        'pago_total_bs' => $cuota->pago_total_bs,
                    ];
                }),
                'archivo_url' => Storage::url($comprobante->archivo)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en verificar comprobante', [
                'message' => $e->getMessage(),
                'cuota_ids' => $request->cuota_ids,
                'inscripcion_id' => $inscripcionId,
                'cuotas_encontradas' => $cuotas->pluck('id')->toArray()
            ]);
            throw $e;
        }
    }

    /**
     * Verificar comprobante y registrar pago (usando el mismo flujo que en EstudiantesController)
     */
    public function verificar(Request $request, $id)
    {
        \Log::info('Verificando comprobante ID: ' . $id);
        \Log::info('Datos completos:', $request->all());
        \Log::info('IDs de cuotas:', $request->cuota_ids);


        $comprobante = PagoRespaldo::with('inscripcion')->findOrFail($id);

        if ($comprobante->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'El comprobante ya fue procesado.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Verificar que las cuotas pertenezcan a la misma inscripción
            $inscripcionId = $comprobante->inscripcion->id;
            $cuotas = Cuota::whereIn('id', $request->cuota_ids)
                ->where('inscripcione_id', $inscripcionId)
                ->get();

            if ($cuotas->count() != count($request->cuota_ids)) {
                throw new \Exception('Alguna cuota no pertenece a la inscripción del comprobante.');
            }

            // Calcular monto total pendiente de las cuotas seleccionadas
            $totalPendiente = $cuotas->sum('pago_pendiente_bs');
            $montoPagar = $request->monto_pago;
            $descuento = $request->descuento_bs ?? 0;
            $neto = $montoPagar - $descuento;

            if ($neto <= 0) {
                throw new \Exception('El monto neto a pagar debe ser mayor a cero.');
            }

            // Generar número de recibo (similar a como se hace en EstudiantesController)
            $ultimoPago = Pago::orderBy('id', 'desc')->first();
            $numeroRecibo = $ultimoPago ? intval(substr($ultimoPago->recibo, -9)) + 1 : 1;
            $recibo = 'UNIP-' . str_pad($numeroRecibo, 9, '0', STR_PAD_LEFT);

            // Crear el pago
            $pago = new Pago();
            $pago->recibo = $recibo;
            $pago->pago_bs = $neto;
            $pago->descuento_bs = $descuento;
            $pago->fecha_pago = $request->fecha_pago;
            $pago->tipo_pago = $request->tipo_pago;
            $pago->estado = 'completado';
            $pago->n_comprobante = $request->n_comprobante;

            // Asignar cuenta bancaria o caja según tipo
            if ($request->tipo_pago === 'Efectivo') {
                $pago->caja_id = $request->caja_id;
            } else {
                $pago->cuenta_bancaria_id = $request->cuenta_bancaria_id;
            }

            // Obtener el trabajador_cargo_id del usuario actual
            $user = auth()->user();
            $trabajadorCargo = $user->persona->trabajador->trabajadores_cargos()
                ->where('estado', 'Vigente')
                ->first();
            if ($trabajadorCargo) {
                $pago->trabajadore_cargo_id = $trabajadorCargo->id;
            }

            $pago->save();

            // Crear detalle de pago (un solo detalle, aunque podría dividirse)
            $detalle = new Detalle();
            $detalle->pago_id = $pago->id;
            $detalle->pago_bs = $neto;
            $detalle->tipo_pago = $request->tipo_pago;
            $detalle->save();

            // Asignar pago a las cuotas
            $montoRestante = $neto;
            foreach ($cuotas as $cuota) {
                if ($montoRestante <= 0) break;

                $montoCuota = min($cuota->pago_pendiente_bs, $montoRestante);
                if ($montoCuota > 0) {
                    // Crear pago_cuota
                    $pagoCuota = new PagosCuota();
                    $pagoCuota->cuota_id = $cuota->id;
                    $pagoCuota->pago_id = $pago->id;
                    $pagoCuota->pago_bs = $montoCuota;
                    $pagoCuota->save();

                    // Actualizar cuota
                    $cuota->pago_pendiente_bs -= $montoCuota;
                    if ($cuota->pago_pendiente_bs <= 0) {
                        $cuota->pago_terminado = 'si';
                        $cuota->pago_pendiente_bs = 0;
                    }
                    $cuota->save();

                    $montoRestante -= $montoCuota;
                }
            }

            // Si sobra monto, podría manejarse como anticipo, pero por ahora solo log
            if ($montoRestante > 0) {
                \Log::warning("Sobrante de pago no asignado: {$montoRestante} Bs en pago ID {$pago->id}");
            }

            // Marcar comprobante como verificado
            $comprobante->estado = 'verificado';
            $comprobante->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado y comprobante verificado correctamente.',
                'pago_id' => $pago->id,
                'recibo' => $pago->recibo
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al verificar comprobante: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechazar comprobante
     */
    public function rechazar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'motivo_rechazo' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comprobante = PagoRespaldo::findOrFail($id);

        if ($comprobante->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'El comprobante ya fue procesado.'
            ], 422);
        }

        $comprobante->estado = 'rechazado';
        $comprobante->observaciones = ($comprobante->observaciones ? $comprobante->observaciones . "\n" : '') .
            'Rechazado: ' . $request->motivo_rechazo;
        $comprobante->save();

        return response()->json([
            'success' => true,
            'message' => 'Comprobante rechazado correctamente.'
        ]);
    }
}
