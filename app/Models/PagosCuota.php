<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagosCuota extends Model
{
    protected $table = 'pagos_cuotas';

    protected $fillable = [
        'cuota_id',
        'pago_id',
        'pago_bs',
    ];

    public function cuota()
    {
        return $this->belongsTo(Cuota::class, 'cuota_id');
    }

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }
}
