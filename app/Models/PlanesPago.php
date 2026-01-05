<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanesPago extends Model
{
    protected $table = 'planes_pagos';

    protected $fillable = [
        'nombre',
    ];

    public function plan_concepto()
    {
        return $this->hasMany(PlanesConcepto::class, 'planes_pago_id');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcione::class, 'planes_pago_id');
    }
}
