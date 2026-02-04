<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientosBancarios extends Model
{
    protected $table = 'movimientos_bancarios';

    protected $fillable = [
        'cuenta_bancaria_id',
        'tipo_movimiento',
        'monto',
        'saldo_anterior',
        'saldo_posterior',
        'descripcion',
        'referencia_id',
        'referencia_type',
        'trabajadore_cargo_id',
        'conciliado',
        'fecha_conciliacion',
        'conciliacion_id'
    ];

    public function cuentaBancaria()
    {
        return $this->belongsTo(CuentasBancarias::class, 'cuenta_bancaria_id');
    }

    public function referencia()
    {
        return $this->morphTo();
    }

    public function usuario()
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'trabajadore_cargo_id');
    }

    public function conciliacion()
    {
        return $this->belongsTo(ConciliacionesBancarias::class, 'conciliacion_id');
    }
}
