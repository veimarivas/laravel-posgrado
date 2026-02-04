<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $table = 'bancos';

    protected $fillable = [
        'nombre',
        'codigo',
        'color',
        'logo'
    ];

    public function cuentas()
    {
        return $this->hasMany(CuentasBancarias::class, 'banco_id');
    }

    // Relación con pagos a través de cuentas
    public function pagos()
    {
        return $this->hasManyThrough(Pago::class, CuentasBancarias::class, 'banco_id', 'cuenta_bancaria_id');
    }

    // Agregar relación con transferencias
    public function transferenciasEnviadas()
    {
        return $this->hasManyThrough(
            TransferenciasBancarias::class,
            CuentasBancarias::class,
            'banco_id',
            'cuenta_origen_id'
        );
    }

    public function transferenciasRecibidas()
    {
        return $this->hasManyThrough(
            TransferenciasBancarias::class,
            CuentasBancarias::class,
            'banco_id',
            'cuenta_destino_id'
        );
    }

    // Método para contar cuentas activas
    public function cuentasActivas()
    {
        return $this->cuentas()->where('activa', true);
    }

    // Método para contar cuentas inactivas
    public function cuentasInactivas()
    {
        return $this->cuentas()->where('activa', false);
    }
}
