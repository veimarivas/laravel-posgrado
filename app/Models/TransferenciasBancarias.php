<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferenciasBancarias extends Model
{
    protected $table = 'transferencias_bancarias';

    protected $fillable = [
        'cuenta_origen_id',
        'cuenta_destino_id',
        'monto',
        'moneda',
        'tasa_cambio',
        'fecha_transferencia',
        'fecha_efectiva',
        'comprobante',
        'descripcion',
        'tipo_transferencia',
        'estado',
        'motivo_correccion',
        'pago_id',
        'trabajadore_cargo_id'
    ];

    public function cuentaOrigen()
    {
        return $this->belongsTo(CuentasBancarias::class, 'cuenta_origen_id');
    }

    public function cuentaDestino()
    {
        return $this->belongsTo(CuentasBancarias::class, 'cuenta_destino_id');
    }

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }

    public function usuario()
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'trabajadore_cargo_id');
    }

    public function getTipoTransferenciaFormateadoAttribute()
    {
        $tipos = [
            'interbancaria' => 'Interbancaria',
            'intrabancaria' => 'Mismo Banco',
            'correccion' => 'Corrección de Asignación',
            'ajuste' => 'Ajuste Contable'
        ];

        return $tipos[$this->tipo_transferencia] ?? $this->tipo_transferencia;
    }
}
