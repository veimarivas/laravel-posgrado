<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfertasAcademica extends Model
{
    protected $table = 'ofertas_academicas';

    protected $fillable = [
        'codigo',
        'sucursale_id',
        'modalidade_id',
        'posgrado_id',
        'programa_id',
        'fecha_inicio_inscripciones',
        'fecha_inicio_programa',
        'fecha_fin_programa',
        'gestion',
        'n_modulos',
        'cantidad_sesiones',
        'version',
        'grupo',
        'nota_minima',
        'portada',
        'certificado',
        'responsable_marketing_cargo_id',
        'responsable_academico_cargo_id',
        'color',
        'fase_id'
    ];

    // App\Models\OfertasAcademica.php
    protected $casts = [
        'fecha_inicio_inscripciones' => 'datetime:Y-m-d',
        'fecha_inicio_programa' => 'datetime:Y-m-d',
        'fecha_fin_programa' => 'datetime:Y-m-d',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursale::class, 'sucursale_id');
    }

    public function modalidad()
    {
        return $this->belongsTo(Modalidade::class, 'modalidade_id');
    }

    public function posgrado()
    {
        return $this->belongsTo(Posgrado::class, 'posgrado_id');
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'programa_id');
    }

    public function plan_concepto()
    {
        return $this->hasMany(PlanesConcepto::class, 'ofertas_academica_id');
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class, 'ofertas_academica_id');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcione::class, 'ofertas_academica_id');
    }

    public function fase()
    {
        return $this->belongsTo(Fase::class, 'fase_id');
    }

    public function totalInscritos()
    {
        return $this->inscripciones()->where('estado', 'Inscrito')->count();
    }

    public function totalPreInscritos()
    {
        return $this->inscripciones()->where('estado', 'Pre-Inscrito')->count();
    }
}
