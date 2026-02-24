<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    protected $table = 'cuotas';

    protected $fillable = [
        'nombre',
        'n_cuota',
        'pago_total_bs',
        'pago_pendiente_bs',
        'descuento_bs',
        'fecha_pago',
        'pago_terminado',
        'inscripcione_id',
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcione::class, 'inscripcione_id');
    }

    public function pagos_cuotas()
    {
        return $this->hasMany(PagosCuota::class, 'cuota_id');
    }

    public function pagosrespaldo()
    {
        return $this->hasMany(PagoRespaldo::class, 'cuota_id');
    }
}
