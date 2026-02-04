<?php

namespace App\Services;

use App\Models\{
    CuentasBancarias,
    TransferenciaBancaria,
    MovimientoBancario,
    MovimientoCaja,
    MovimientosBancarios,
    TransferenciasBancarias
};
use Illuminate\Support\Facades\DB;

class TransferenciaService
{
    public function transferirEntreCuentas($data)
    {
        return DB::transaction(function () use ($data) {
            // Validar saldo suficiente
            $cuentaOrigen = CuentasBancarias::findOrFail($data['cuenta_origen_id']);

            if ($cuentaOrigen->saldo_actual < $data['monto']) {
                throw new \Exception('Saldo insuficiente en la cuenta de origen');
            }

            // Crear transferencia
            $transferencia = TransferenciasBancarias::create($data);

            // Ajustar saldos
            $cuentaDestino = CuentasBancarias::findOrFail($data['cuenta_destino_id']);

            // Restar de cuenta origen
            $saldoAnteriorOrigen = $cuentaOrigen->saldo_actual;
            $cuentaOrigen->saldo_actual -= $data['monto'];
            $cuentaOrigen->save();

            // Sumar a cuenta destino
            $saldoAnteriorDestino = $cuentaDestino->saldo_actual;
            $cuentaDestino->saldo_actual += $data['monto'];
            $cuentaDestino->save();

            // Registrar movimientos
            MovimientosBancarios::create([
                'cuenta_bancaria_id' => $cuentaOrigen->id,
                'tipo_movimiento' => 'transferencia_envio',
                'monto' => -$data['monto'],
                'saldo_anterior' => $saldoAnteriorOrigen,
                'saldo_posterior' => $cuentaOrigen->saldo_actual,
                'descripcion' => $data['descripcion'] ?? "Transferencia a cuenta {$cuentaDestino->numero_cuenta}",
                'referencia_id' => $transferencia->id,
                'referencia_type' => TransferenciasBancarias::class,
                'trabajadore_cargo_id' => $data['user_id']
            ]);

            MovimientosBancarios::create([
                'cuenta_bancaria_id' => $cuentaDestino->id,
                'tipo_movimiento' => 'transferencia_recepcion',
                'monto' => $data['monto'],
                'saldo_anterior' => $saldoAnteriorDestino,
                'saldo_posterior' => $cuentaDestino->saldo_actual,
                'descripcion' => $data['descripcion'] ?? "Transferencia de cuenta {$cuentaOrigen->numero_cuenta}",
                'referencia_id' => $transferencia->id,
                'referencia_type' => TransferenciasBancarias::class,
                'trabajadore_cargo_id' => $data['user_id']
            ]);

            // Actualizar estado de transferencia
            $transferencia->estado = 'procesada';
            $transferencia->save();

            return $transferencia;
        });
    }

    public function depositarDeCaja($cajaId, $cuentaBancariaId, $monto, $userId, $descripcion = null)
    {
        return DB::transaction(function () use ($cajaId, $cuentaBancariaId, $monto, $userId, $descripcion) {
            // Lógica para depósito desde caja a cuenta bancaria
            // Implementar según sea necesario
        });
    }
}
