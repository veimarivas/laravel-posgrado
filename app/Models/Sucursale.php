<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursale extends Model
{
    protected $table = 'sucursales';

    protected $fillable = [
        'nombre',
        'latitud',
        'longitud',
        'sede_id',
        'color',
        'direccion'
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function ofertas_academicas()
    {
        return $this->hasMany(OfertasAcademica::class, 'sucursale_id');
    }

    public function sucursales_cuentas()
    {
        return $this->hasMany(SucursalesCuenta::class, 'sucursale_id');
    }

    public function trabajadores_cargos()
    {
        return $this->hasMany(TrabajadoresCargo::class, 'trabajadores_cargo_id');
    }
}
