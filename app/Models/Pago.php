<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'recibo',
        'cuenta_bancaria_id',
        'caja_id',
        'deposito_id',
        'n_comprobante',
        'pago_bs',
        'descuento_bs',
        'fecha_pago',
        'tipo_pago',
        'estado',
        'trabajadore_cargo_id' // Agregar este campo
    ];


    public function pagos_cuotas()
    {
        return $this->hasMany(PagosCuota::class, 'pago_id');
    }

    public function detalles()
    {
        return $this->hasMany(Detalle::class, 'pago_id');
    }

    public function cuenta_bancaria()
    {
        return $this->belongsTo(CuentasBancarias::class, 'cuenta_bancaria_id');
    }

    public function caja()
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }

    public function deposito()
    {
        return $this->belongsTo(Deposito::class, 'deposito_id');
    }

    public function trabajadorCargo()
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'trabajadore_cargo_id');
    }


    public function transferenciasCorreccion()
    {
        return $this->hasMany(TransferenciasBancarias::class, 'pago_id')
            ->where('tipo_transferencia', 'correccion');
    }

    public function movimientosBancarios()
    {
        return $this->morphMany(MovimientosBancarios::class, 'referencia');
    }

    public function getTipoPagoFormateadoAttribute()
    {
        $tipos = [
            'transferencia' => 'Transferencia',
            'efectivo' => 'Efectivo',
            'deposito' => 'Depósito'
        ];

        return $tipos[$this->tipo_pago] ?? $this->tipo_pago;
    }

    // Método para reasignar pago a otra cuenta
    public function reasignarACuenta($nuevaCuentaId, $motivo, $userId)
    {
        return DB::transaction(function () use ($nuevaCuentaId, $motivo, $userId) {
            // 1. Crear transferencia de corrección
            $transferencia = TransferenciasBancarias::create([
                'cuenta_origen_id' => $this->cuenta_bancaria_id,
                'cuenta_destino_id' => $nuevaCuentaId,
                'monto' => $this->pago_bs,
                'moneda' => 'BS',
                'fecha_transferencia' => now(),
                'fecha_efectiva' => now(),
                'tipo_transferencia' => 'correccion',
                'motivo_correccion' => $motivo,
                'pago_id' => $this->id,
                'trabajadore_cargo_id' => $userId,
                'estado' => 'procesada'
            ]);

            // 2. Ajustar saldos de las cuentas
            $cuentaOrigen = CuentasBancarias::find($this->cuenta_bancaria_id);
            $cuentaDestino = CuentasBancarias::find($nuevaCuentaId);

            // Restar de cuenta origen
            $saldoAnteriorOrigen = $cuentaOrigen->saldo_actual;
            $cuentaOrigen->saldo_actual -= $this->pago_bs;
            $cuentaOrigen->save();

            // Sumar a cuenta destino
            $saldoAnteriorDestino = $cuentaDestino->saldo_actual;
            $cuentaDestino->saldo_actual += $this->pago_bs;
            $cuentaDestino->save();

            // 3. Registrar movimientos bancarios
            MovimientosBancarios::create([
                'cuenta_bancaria_id' => $this->cuenta_bancaria_id,
                'tipo_movimiento' => 'transferencia_envio',
                'monto' => -$this->pago_bs,
                'saldo_anterior' => $saldoAnteriorOrigen,
                'saldo_posterior' => $cuentaOrigen->saldo_actual,
                'descripcion' => "Corrección de pago #{$this->recibo}",
                'referencia_id' => $transferencia->id,
                'referencia_type' => TransferenciasBancarias::class,
                'trabajadore_cargo_id' => $userId
            ]);

            MovimientosBancarios::create([
                'cuenta_bancaria_id' => $nuevaCuentaId,
                'tipo_movimiento' => 'transferencia_recepcion',
                'monto' => $this->pago_bs,
                'saldo_anterior' => $saldoAnteriorDestino,
                'saldo_posterior' => $cuentaDestino->saldo_actual,
                'descripcion' => "Recepción por corrección de pago #{$this->recibo}",
                'referencia_id' => $transferencia->id,
                'referencia_type' => TransferenciasBancarias::class,
                'trabajadore_cargo_id' => $userId
            ]);

            // 4. Actualizar pago con nueva cuenta
            $cuentaAnterior = $this->cuenta_bancaria_id;
            $this->cuenta_bancaria_id = $nuevaCuentaId;
            $this->save();

            return [
                'transferencia' => $transferencia,
                'cuenta_anterior' => $cuentaAnterior,
                'cuenta_nueva' => $nuevaCuentaId
            ];
        });
    }
}
