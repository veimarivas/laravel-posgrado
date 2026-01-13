<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PlanesPago extends Model
{
    protected $table = 'planes_pagos';

    protected $fillable = [
        'nombre',
        'habilitado',
        'principal',
        'es_promocion',
        'fecha_inicio_promocion',
        'fecha_fin_promocion'
    ];

    protected $casts = [
        'habilitado' => 'boolean',
        'es_promocion' => 'boolean',
        'principal' => 'boolean',
        'fecha_inicio_promocion' => 'date',
        'fecha_fin_promocion' => 'date'
    ];

    // Scope para obtener planes disponibles (habilitados y promociones vigentes)
    public function scopeDisponibles(Builder $query)
    {
        return $query->where('habilitado', true)
            ->where(function ($q) {
                $q->where('es_promocion', false)
                    ->orWhere(function ($q2) {
                        $q2->where('es_promocion', true)
                            ->where('fecha_inicio_promocion', '<=', now())
                            ->where('fecha_fin_promocion', '>=', now());
                    });
            });
    }

    public function plan_concepto()
    {
        return $this->hasMany(PlanesConcepto::class, 'planes_pago_id');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcione::class, 'planes_pago_id');
    }

    // Método para verificar si la promoción está vigente
    public function getPromocionVigenteAttribute()
    {
        if (!$this->es_promocion) {
            return false;
        }

        $now = now();
        $inicio = $this->fecha_inicio_promocion;
        $fin = $this->fecha_fin_promocion;

        if (!$inicio || !$fin) {
            return false;
        }

        return $now->between($inicio, $fin);
    }

    // Obtener plan principal para la misma oferta
    public function obtenerPlanPrincipal($ofertaId)
    {
        return self::whereHas('plan_concepto', function ($query) use ($ofertaId) {
            $query->where('ofertas_academica_id', $ofertaId);
        })
            ->where('principal', true)
            ->first();
    }
}
