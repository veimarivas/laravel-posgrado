<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = [
        'fecha',
        'hora_inicio',
        'hora_fin',
        'estado',
        'modulo_id',
        'sucursales_cuenta_id',
        'trabajadores_cargo_id',
        'reprogramado_id'
    ];

    // Agrega este cast
    protected $casts = [
        'fecha' => 'date',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function sucursal_cuenta()
    {
        return $this->belongsTo(SucursalesCuenta::class, 'sucursales_cuenta_id');
    }

    public function trabajador_cargo()
    {
        return $this->belongsTo(TrabajadoresCargo::class, 'trabajadores_cargo_id');
    }

    public function reprogramados()
    {
        return $this->hasMany(Horario::class, 'reprogramado_id');
    }

    public function reprogramado()
    {
        return $this->belongsTo(Horario::class, 'reprogramado_id');
    }
}
