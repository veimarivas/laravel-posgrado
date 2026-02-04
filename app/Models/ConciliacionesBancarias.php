<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConciliacionesBancarias extends Model
{
    protected $table = 'conciliaciones_bancarias';

    protected $fillable = [
        'cuenta_bancaria_id',
        'fecha_inicio',
        'fecha_fin',
        'saldo_libros',
        'saldo_extracto',
        'diferencia',
        'estado',
        'observaciones',
        'trabajadore_cargo_id'
    ];

    public function cuentaBancaria()
    {
        return $this->belongsTo(CuentasBancarias::class, 'cuenta_bancaria_id');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientosBancarios::class, 'conciliacion_id');
    }

    public function usuario()
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'trabajadore_cargo_id');
    }
}
