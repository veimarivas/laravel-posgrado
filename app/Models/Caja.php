<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'cajas';

    protected $fillable = [
        'sucursale_id',
        'responsable_id',
        'nombre',
        'descripcion',
        'saldo_actual',
        'saldo_inicial',
        'moneda',
        'activa'
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursale::class, 'sucursale_id');
    }

    public function responsable()
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'responsable_id');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientosCaja::class, 'caja_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'caja_id');
    }

    public function depositos()
    {
        return $this->hasMany(Deposito::class, 'caja_id');
    }
}
