<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcione extends Model
{
    protected $table = 'inscripciones';

    protected $fillable = [
        'ofertas_academica_id',
        'estudiante_id',
        'trabajadores_cargo_id',
        'planes_pago_id',
        'estado',
        'fecha_registro',
        'observacion'
    ];

    protected $casts = [
        'fecha_registro' => 'datetime:Y-m-d H:i:s',
    ];

    public function ofertaAcademica()
    {
        return $this->belongsTo(OfertasAcademica::class, 'ofertas_academica_id');
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    public function matriculaciones()
    {
        return $this->hasMany(Matriculacione::class, 'inscripcione_id');
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class, 'inscripcione_id');
    }

    public function planesPago()
    {
        return $this->belongsTo(PlanesPago::class, 'planes_pago_id');
    }

    public function trabajador_cargo()
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'trabajadores_cargo_id');
    }
}
