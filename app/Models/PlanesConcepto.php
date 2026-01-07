<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class PlanesConcepto extends Model
{
    protected $table = 'planes_conceptos';

    protected $fillable = [
        'n_cuotas',
        'pago_bs',
        'ofertas_academica_id',
        'planes_pago_id',
        'concepto_id',
        'fecha_inicio_promocion',
        'fecha_fin_promocion',
        'es_promocion',
        'precio_regular',
        'descuento_porcentaje'
    ];

    protected $casts = [
        'fecha_inicio_promocion' => 'date',
        'fecha_fin_promocion' => 'date',
        'es_promocion' => 'boolean',
        'precio_regular' => 'decimal:2',
        'descuento_porcentaje' => 'decimal:2',
        'pago_bs' => 'decimal:2'
    ];

    public function oferta_academica()
    {
        return $this->belongsTo(OfertasAcademica::class, 'ofertas_academica_id');
    }

    public function plan_pago()
    {
        return $this->belongsTo(PlanesPago::class, 'planes_pago_id');
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'concepto_id');
    }

    // Método para verificar si la promoción está vigente
    public function getPromocionVigenteAttribute()
    {
        if (!$this->es_promocion) {
            return false;
        }

        $now = Carbon::now();
        $inicio = $this->fecha_inicio_promocion ? Carbon::parse($this->fecha_inicio_promocion) : null;
        $fin = $this->fecha_fin_promocion ? Carbon::parse($this->fecha_fin_promocion) : null;

        if (!$inicio || !$fin) {
            return false;
        }

        return $now->between($inicio, $fin);
    }

    // Método para obtener el precio con descuento si está vigente
    public function getPrecioVigenteAttribute()
    {
        if ($this->es_promocion && $this->promocion_vigente) {
            return $this->pago_bs; // Ya tiene el precio promocional
        }

        return $this->precio_regular ?? $this->pago_bs;
    }

    // Método para calcular el descuento
    public function getDescuentoCalculadoAttribute()
    {
        if ($this->es_promocion && $this->promocion_vigente && $this->precio_regular) {
            return $this->precio_regular - $this->pago_bs;
        }
        return 0;
    }
}
