<?php

namespace App\Services;

use App\Models\{
    Caja,
    MovimientosCaja,
    Pago
};
use Illuminate\Support\Facades\DB;

class CajaService
{
    public function registrarPagoEfectivo($cajaId, $pagoData, $userId)
    {
        return DB::transaction(function () use ($cajaId, $pagoData, $userId) {
            // Registrar el pago en la caja
            $caja = Caja::findOrFail($cajaId);

            // Crear pago
            $pago = Pago::create(array_merge($pagoData, [
                'caja_id' => $cajaId,
                'tipo_pago' => 'efectivo',
                'estado' => 'registrado'
            ]));

            // Registrar movimiento en caja
            MovimientosCaja::create([
                'caja_id' => $cajaId,
                'tipo_movimiento' => 'ingreso',
                'monto' => $pagoData['pago_bs'],
                'saldo_anterior' => $caja->saldo_actual,
                'saldo_posterior' => $caja->saldo_actual + $pagoData['pago_bs'],
                'descripcion' => "Pago en efectivo #{$pago->recibo}",
                'referencia_id' => $pago->id,
                'referencia_type' => Pago::class,
                'trabajadore_cargo_id' => $userId
            ]);

            // Actualizar saldo de caja
            $caja->saldo_actual += $pagoData['pago_bs'];
            $caja->save();

            return $pago;
        });
    }
}
