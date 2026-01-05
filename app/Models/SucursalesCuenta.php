<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SucursalesCuenta extends Model
{
    protected $table = 'sucursales_cuentas';

    protected $fillable = [
        'sucursale_id',
        'cuenta_id',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursale::class, 'sucursale_id');
    }

    public function cuenta()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_id');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'horario_id');
    }
}
