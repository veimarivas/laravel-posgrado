<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientosCaja extends Model
{
    protected $table = 'movimientos_cajas';

    protected $fillable = [
        'caja_id',
        'tipo_movimiento',
        'monto',
        'saldo_anterior',
        'saldo_posterior',
        'descripcion',
        'referencia_id',
        'referencia_type',
        'trabajadore_cargo_id'
    ];

    public function caja()
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }

    public function user()
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'trabajadore_cargo_id');
    }

    public function referencia()
    {
        return $this->morphTo();
    }
}
