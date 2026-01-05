<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $table = 'cuentas';

    protected $fillable = [
        'correo',
        'cantidad_sesiones',
    ];

    public function sucursales_cuentas()
    {
        return $this->hasMany(SucursalesCuenta::class, 'cuenta_id');
    }
}
