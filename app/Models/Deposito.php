<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    protected $table = 'depositos';

    protected $fillable = [
        'caja_id',
        'cuenta_bancaria_id',
        'monto',
        'fecha_deposito',
        'comprobante',
        'descripcion',
        'estado',
        'trabajadore_cargo_id'
    ];

    public function caja()
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }

    public function cuentaBancaria()
    {
        return $this->belongsTo(CuentasBancarias::class, 'cuenta_bancaria_id');
    }

    public function user()
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'trabajadore_cargo_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'deposito_id');
    }
}
