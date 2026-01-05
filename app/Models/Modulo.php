<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulos';

    protected $fillable = [
        'n_modulo',
        'nombre',
        'estado',
        'color',
        'fecha_inicio',
        'fecha_fin',
        'docente_id',
        'ofertas_academica_id'
    ];

    // Agrega estos casts
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function oferta_academica()
    {
        return $this->belongsTo(OfertasAcademica::class, 'ofertas_academica_id');
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'modulo_id');
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id');
    }

    public function matriculaciones()
    {
        return $this->hasMany(Matriculacione::class, 'inscripcione_id');
    }
}
