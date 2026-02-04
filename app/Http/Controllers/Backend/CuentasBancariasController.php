<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CuentaBancaria;
use App\Models\Banco;
use App\Models\ConciliacionesBancarias;
use App\Models\CuentasBancarias;
use App\Models\MovimientosBancarios;
use App\Models\Sucursale;
use App\Models\TransferenciasBancarias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuentasBancariasController extends Controller
{
    public function listar(Request $request)
    {
        $search = $request->get('search', '');

        $query = CuentasBancarias::with(['banco', 'sucursal']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_cuenta', 'like', "%{$search}%")
                    ->orWhereHas('banco', function ($subq) use ($search) {
                        $subq->where('nombre', 'like', "%{$search}%");
                    })
                    ->orWhereHas('sucursal', function ($subq) use ($search) {
                        $subq->where('nombre', 'like', "%{$search}%");
                    });
            });
        }

        $cuentas = $query->paginate(10)->appends(['search' => $search]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.cuentas_bancarias.partials.table-body', compact('cuentas'))->render(),
                'pagination' => $cuentas->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.cuentas_bancarias.listar', compact('cuentas'));
    }

    public function listarActivas()
    {
        try {
            $cuentas = CuentasBancarias::where('activa', true)
                ->with(['banco:id,nombre', 'sucursal:id,nombre'])
                ->get(['id', 'banco_id', 'numero_cuenta', 'moneda', 'saldo_actual'])
                ->map(function ($cuenta) {
                    return [
                        'id' => $cuenta->id,
                        'banco_nombre' => $cuenta->banco->nombre ?? 'Sin banco',
                        'numero_cuenta' => $cuenta->numero_cuenta,
                        'moneda' => $cuenta->moneda,
                        'saldo_actual' => $cuenta->saldo_actual,
                    ];
                });

            return response()->json([
                'success' => true,
                'cuentas' => $cuentas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar cuentas bancarias'
            ], 500);
        }
    }

    // En el método ver() del CuentasBancariasController
    public function ver($id)
    {
        // Cargar la cuenta con relaciones básicas primero
        $cuenta = CuentasBancarias::with([
            'banco',
            'sucursal.sede',
            'pagos' => function ($query) {
                $query->orderBy('fecha_pago', 'desc')
                    ->with([
                        'pagos_cuotas.cuota.inscripcion.estudiante.persona', // Relación corregida
                        'pagos_cuotas.cuota.inscripcion.ofertaAcademica.programa' // Agregar programa
                    ])
                    ->limit(10);
            }
        ])->findOrFail($id);

        // Calcular estadísticas básicas
        $totalPagos = $cuenta->pagos()->count();
        $campoMonto = 'pago_bs'; // Usar el campo correcto según tu BD
        $totalDepositado = $cuenta->pagos()->sum($campoMonto);

        // Obtener pagos recientes (ya cargados)
        $pagosRecientes = $cuenta->pagos;

        // Procesar cada pago para agregar información del estudiante
        foreach ($pagosRecientes as $pago) {
            // Obtener el primer estudiante de los pagos_cuotas
            $primerPagoCuota = $pago->pagos_cuotas->first();
            if ($primerPagoCuota && $primerPagoCuota->cuota && $primerPagoCuota->cuota->inscripcion) {
                $pago->estudiante = $primerPagoCuota->cuota->inscripcion->estudiante;
                $pago->inscripcion = $primerPagoCuota->cuota->inscripcion;
            } else {
                $pago->estudiante = null;
                $pago->inscripcion = null;
            }
        }

        // Obtener movimientos por mes
        $movimientosPorMes = DB::table('movimientos_bancarios')
            ->where('cuenta_bancaria_id', $cuenta->id)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(monto) as total')
            )
            ->groupBy('mes')
            ->orderBy('mes', 'desc')
            ->take(12)
            ->get();

        // Cargar relaciones del sistema contable por separado
        $transferenciasEnviadas = $cuenta->transferenciasEnviadas()
            ->orderBy('fecha_transferencia', 'desc')
            ->with(['cuentaDestino.banco', 'cuentaDestino.sucursal', 'usuario'])
            ->get();

        $transferenciasRecibidas = $cuenta->transferenciasRecibidas()
            ->orderBy('fecha_transferencia', 'desc')
            ->with(['cuentaOrigen.banco', 'cuentaOrigen.sucursal', 'usuario'])
            ->get();

        $transferenciasCorreccion = $cuenta->transferenciasCorreccion()
            ->orderBy('fecha_transferencia', 'desc')
            ->with(['cuentaDestino.banco', 'pago', 'usuario'])
            ->get();

        $depositos = $cuenta->depositos()
            ->orderBy('fecha_deposito', 'desc')
            ->with(['caja.sucursal', 'user'])
            ->get();

        $movimientos = $cuenta->movimientos()
            ->orderBy('created_at', 'desc')
            ->with(['referencia', 'usuario'])
            ->paginate(20);

        $conciliaciones = $cuenta->conciliaciones()
            ->orderBy('fecha_fin', 'desc')
            ->with(['usuario'])
            ->get();

        return view('admin.cuentas_bancarias.ver', compact(
            'cuenta',
            'totalPagos',
            'totalDepositado',
            'pagosRecientes',
            'movimientosPorMes',
            'transferenciasEnviadas',
            'transferenciasRecibidas',
            'transferenciasCorreccion',
            'depositos',
            'movimientos',
            'conciliaciones',
            'campoMonto'
        ));
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'sucursale_id' => 'required|exists:sucursales,id',
            'numero_cuenta' => 'required|string|max:50',
            'tipo_cuenta' => 'required|in:ahorro,corriente,moneda_extranjera',
            'moneda' => 'required|in:BS,USD,EUR',
            'descripcion' => 'nullable|string',
            'activa' => 'nullable|boolean', // Cambiado a nullable
            'saldo_inicial' => 'required|numeric|min:0'
        ]);

        // Verificar que la combinación banco + número de cuenta no exista para la misma sucursal
        $existe = CuentasBancarias::where('banco_id', $request->banco_id)
            ->where('sucursale_id', $request->sucursale_id)
            ->where('numero_cuenta', $request->numero_cuenta)
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'msg' => 'Ya existe una cuenta con este número en el banco y sucursal seleccionados.'
            ], 422);
        }

        $cuenta = CuentasBancarias::create([
            'banco_id' => $request->banco_id,
            'sucursale_id' => $request->sucursale_id,
            'numero_cuenta' => $request->numero_cuenta,
            'tipo_cuenta' => $request->tipo_cuenta,
            'moneda' => $request->moneda,
            'descripcion' => $request->descripcion,
            'activa' => $request->boolean('activa'), // Usar boolean() para convertir
            'saldo_inicial' => $request->saldo_inicial,
            'saldo_actual' => $request->saldo_inicial
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Cuenta bancaria registrada correctamente.',
            'cuenta' => $cuenta
        ]);
    }

    public function verificarCuenta(Request $request)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'sucursale_id' => 'required|exists:sucursales,id',
            'numero_cuenta' => 'required|string',
            'id' => 'nullable|numeric'
        ]);

        $query = CuentasBancarias::where('banco_id', $request->banco_id)
            ->where('sucursale_id', $request->sucursale_id)
            ->where('numero_cuenta', $request->numero_cuenta);

        if ($request->has('id') && $request->id) {
            $query->where('id', '!=', $request->id);
        }

        return response()->json(['exists' => $query->exists()]);
    }

    public function modificar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cuentas_bancarias,id',
            'banco_id' => 'required|exists:bancos,id',
            'sucursale_id' => 'required|exists:sucursales,id',
            'numero_cuenta' => 'required|string|max:50',
            'tipo_cuenta' => 'required|in:ahorro,corriente,moneda_extranjera',
            'moneda' => 'required|in:BS,USD,EUR',
            'descripcion' => 'nullable|string',
            'activa' => 'nullable|boolean', // Cambiado a nullable
            'saldo_inicial' => 'required|numeric|min:0'
        ]);

        // Verificar que la combinación banco + número de cuenta no exista en otra cuenta (excluyendo la actual)
        $existe = CuentasBancarias::where('banco_id', $request->banco_id)
            ->where('sucursale_id', $request->sucursale_id)
            ->where('numero_cuenta', $request->numero_cuenta)
            ->where('id', '!=', $request->id)
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'msg' => 'Ya existe otra cuenta con este número en el banco y sucursal seleccionados.'
            ], 422);
        }

        $cuenta = CuentasBancarias::find($request->id);

        // Recalcular saldo_actual si cambia el saldo_inicial
        $saldo_inicial_anterior = $cuenta->saldo_inicial;
        $saldo_actual_anterior = $cuenta->saldo_actual;
        $nuevo_saldo_inicial = $request->saldo_inicial;

        // La diferencia entre el nuevo saldo inicial y el anterior se suma al saldo actual
        $diferencia = $nuevo_saldo_inicial - $saldo_inicial_anterior;
        $nuevo_saldo_actual = $saldo_actual_anterior + $diferencia;

        $cuenta->update([
            'banco_id' => $request->banco_id,
            'sucursale_id' => $request->sucursale_id,
            'numero_cuenta' => $request->numero_cuenta,
            'tipo_cuenta' => $request->tipo_cuenta,
            'moneda' => $request->moneda,
            'descripcion' => $request->descripcion,
            'activa' => $request->boolean('activa'), // Usar boolean() para convertir
            'saldo_inicial' => $nuevo_saldo_inicial,
            'saldo_actual' => $nuevo_saldo_actual
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Cuenta bancaria actualizada correctamente.',
            'cuenta' => $cuenta
        ]);
    }

    public function eliminar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cuentas_bancarias,id'
        ]);

        $cuenta = CuentasBancarias::findOrFail($request->id);

        // Verificar si la cuenta tiene pagos asociados
        if ($cuenta->pagos()->count() > 0) {
            return response()->json([
                'success' => false,
                'msg' => 'No se puede eliminar la cuenta bancaria porque tiene pagos asociados.'
            ], 400);
        }

        $cuenta->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Cuenta bancaria eliminada correctamente.'
        ]);
    }

    public function porBanco(Request $request)
    {
        $cuentas = CuentasBancarias::where('banco_id', $request->banco_id)
            ->where('activa', true)
            ->get(['id', 'numero_cuenta', 'tipo_cuenta', 'moneda']);

        return response()->json($cuentas);
    }

    public function porSucursal(Request $request)
    {
        $cuentas = CuentasBancarias::where('sucursale_id', $request->sucursale_id)
            ->where('activa', true)
            ->get(['id', 'numero_cuenta', 'banco_id', 'tipo_cuenta', 'moneda']);

        return response()->json($cuentas);
    }

    // En CuentasBancariasController.php

    public function obtenerBancos()
    {
        try {
            // Si el modelo Banco tiene campo 'activo', usa where('activo', true)
            // Si no tiene campo activo, simplemente obtén todos
            $bancos = Banco::orderBy('nombre', 'asc')->get(['id', 'nombre', 'codigo']);

            return response()->json([
                'success' => true,
                'bancos' => $bancos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener bancos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerSucursales()
    {
        try {
            $sucursales = Sucursale::orderBy('nombre', 'asc')->get(['id', 'nombre', 'direccion']);

            return response()->json([
                'success' => true,
                'sucursales' => $sucursales
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener sucursales: ' . $e->getMessage()
            ], 500);
        }
    }

    // Nuevos métodos para el sistema contable
    public function porBancoSucursal(Request $request)
    {
        $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'sucursale_id' => 'required|exists:sucursales,id',
            'excluir' => 'nullable|exists:cuentas_bancarias,id'
        ]);

        $query = CuentasBancarias::where('banco_id', $request->banco_id)
            ->where('sucursale_id', $request->sucursale_id)
            ->where('activa', true);

        if ($request->has('excluir') && $request->excluir) {
            $query->where('id', '!=', $request->excluir);
        }

        $cuentas = $query->get(['id', 'numero_cuenta', 'tipo_cuenta', 'moneda']);

        return response()->json($cuentas);
    }

    public function transferir(Request $request)
    {
        $request->validate([
            'cuenta_origen_id' => 'required|exists:cuentas_bancarias,id',
            'cuenta_destino_id' => 'required|exists:cuentas_bancarias,id',
            'monto' => 'required|numeric|min:0.01',
            'descripcion' => 'required|string|max:255',
            'fecha_transferencia' => 'required|date',
            'tipo_transferencia' => 'required|in:interbancaria,intrabancaria,correccion,ajuste'
        ]);

        try {
            DB::beginTransaction();

            $cuentaOrigen = CuentasBancarias::findOrFail($request->cuenta_origen_id);
            $cuentaDestino = CuentasBancarias::findOrFail($request->cuenta_destino_id);

            // Verificar saldo suficiente
            if ($cuentaOrigen->saldo_actual < $request->monto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo insuficiente en la cuenta de origen'
                ], 422);
            }

            // Crear transferencia
            $transferencia = TransferenciasBancarias::create([
                'cuenta_origen_id' => $request->cuenta_origen_id,
                'cuenta_destino_id' => $request->cuenta_destino_id,
                'monto' => $request->monto,
                'descripcion' => $request->descripcion,
                'fecha_transferencia' => $request->fecha_transferencia,
                'fecha_efectiva' => now(),
                'tipo_transferencia' => $request->tipo_transferencia,
                'estado' => 'procesada',
                'user_id' => auth()->id()
            ]);

            // Actualizar saldos
            $cuentaOrigen->saldo_actual -= $request->monto;
            $cuentaOrigen->save();

            $cuentaDestino->saldo_actual += $request->monto;
            $cuentaDestino->save();

            // Registrar movimientos bancarios
            MovimientosBancarios::create([
                'cuenta_bancaria_id' => $cuentaOrigen->id,
                'tipo_movimiento' => 'transferencia_envio',
                'monto' => -$request->monto,
                'saldo_anterior' => $cuentaOrigen->saldo_actual + $request->monto,
                'saldo_posterior' => $cuentaOrigen->saldo_actual,
                'descripcion' => $request->descripcion,
                'referencia_id' => $transferencia->id,
                'referencia_type' => TransferenciasBancarias::class,
                'user_id' => auth()->id()
            ]);

            MovimientosBancarios::create([
                'cuenta_bancaria_id' => $cuentaDestino->id,
                'tipo_movimiento' => 'transferencia_recepcion',
                'monto' => $request->monto,
                'saldo_anterior' => $cuentaDestino->saldo_actual - $request->monto,
                'saldo_posterior' => $cuentaDestino->saldo_actual,
                'descripcion' => $request->descripcion,
                'referencia_id' => $transferencia->id,
                'referencia_type' => TransferenciasBancarias::class,
                'user_id' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transferencia realizada correctamente',
                'transferencia' => $transferencia
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la transferencia: ' . $e->getMessage()
            ], 500);
        }
    }

    public function conciliar(Request $request)
    {
        $request->validate([
            'cuenta_bancaria_id' => 'required|exists:cuentas_bancarias,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'saldo_libros' => 'required|numeric',
            'saldo_extracto' => 'required|numeric',
            'observaciones' => 'nullable|string'
        ]);

        try {
            $diferencia = $request->saldo_libros - $request->saldo_extracto;

            $conciliacion = ConciliacionesBancarias::create([
                'cuenta_bancaria_id' => $request->cuenta_bancaria_id,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'saldo_libros' => $request->saldo_libros,
                'saldo_extracto' => $request->saldo_extracto,
                'diferencia' => $diferencia,
                'observaciones' => $request->observaciones,
                'estado' => 'conciliada',
                'user_id' => auth()->id()
            ]);

            // Marcar movimientos como conciliados
            MovimientosBancarios::where('cuenta_bancaria_id', $request->cuenta_bancaria_id)
                ->whereBetween('created_at', [$request->fecha_inicio, $request->fecha_fin])
                ->update([
                    'conciliado' => true,
                    'fecha_conciliacion' => now(),
                    'conciliacion_id' => $conciliacion->id
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Conciliación realizada correctamente',
                'conciliacion' => $conciliacion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la conciliación: ' . $e->getMessage()
            ], 500);
        }
    }

    public function detalleTransferencia($id)
    {
        $transferencia = TransferenciasBancarias::with([
            'cuentaOrigen.banco',
            'cuentaOrigen.sucursal',
            'cuentaDestino.banco',
            'cuentaDestino.sucursal',
            'usuario',
            'pago'
        ])->findOrFail($id);

        return view('admin.cuentas_bancarias.modals.detalle_transferencia', compact('transferencia'));
    }

    public function detalleConciliacion($id)
    {
        $conciliacion = ConciliacionesBancarias::with([
            'cuentaBancaria.banco',
            'usuario',
            'movimientos' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        return view('admin.cuentas_bancarias.modals.detalle_conciliacion', compact('conciliacion'));
    }
}
