<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentasBancarias extends Model
{
    protected $table = 'cuentas_bancarias';

    protected $fillable = [
        'sucursale_id',
        'banco_id',
        'numero_cuenta',
        'tipo_cuenta', // ahorro, corriente, etc.
        'moneda', // Bs, USD, etc.
        'descripcion',
        'activa',
        'saldo_inicial',
        'saldo_actual'
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursale::class, 'sucursale_id');
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'cuenta_bancaria_id');
    }

    public function depositos()
    {
        return $this->hasMany(Deposito::class, 'cuenta_bancaria_id');
    }

    public function transferenciasEnviadas()
    {
        return $this->hasMany(TransferenciasBancarias::class, 'cuenta_origen_id');
    }

    public function transferenciasRecibidas()
    {
        return $this->hasMany(TransferenciasBancarias::class, 'cuenta_destino_id');
    }

    // Relación para transferencias de corrección (enviadas)
    public function transferenciasCorreccion()
    {
        return $this->hasMany(TransferenciasBancarias::class, 'cuenta_origen_id')
            ->where('tipo_transferencia', 'correccion');
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientosBancarios::class, 'cuenta_bancaria_id');
    }

    public function conciliaciones()
    {
        return $this->hasMany(ConciliacionesBancarias::class, 'cuenta_bancaria_id');
    }

    public function pagosDepositados()
    {
        return $this->hasMany(Pago::class, 'cuenta_bancaria_id')
            ->where('estado', 'depositado');
    }

    // Accesores para mostrar información formateada
    public function getTipoCuentaFormateadoAttribute()
    {
        $tipos = [
            'ahorro' => 'Ahorro',
            'corriente' => 'Corriente',
            'moneda_extranjera' => 'Moneda Extranjera'
        ];

        return $tipos[$this->tipo_cuenta] ?? $this->tipo_cuenta;
    }

    public function getMonedaSimboloAttribute()
    {
        $simbolos = [
            'BS' => 'Bs',
            'USD' => '$',
            'EUR' => '€'
        ];

        return $simbolos[$this->moneda] ?? $this->moneda;
    }

    public function getEstadoFormateadoAttribute()
    {
        return $this->activa ? 'Activa' : 'Inactiva';
    }

    public function getEstadoBadgeAttribute()
    {
        return $this->activa
            ? '<span class="badge bg-success">Activa</span>'
            : '<span class="badge bg-secondary">Inactiva</span>';
    }

    public function getSaldoActualFormateadoAttribute()
    {
        $simbolo = $this->moneda_simbolo;
        $saldo = number_format($this->saldo_actual, 2);
        $clase = $this->saldo_actual >= 0 ? 'text-success' : 'text-danger';

        return "<span class='{$clase}'><strong>{$simbolo} {$saldo}</strong></span>";
    }

    public function getSaldoInicialFormateadoAttribute()
    {
        return $this->moneda_simbolo . ' ' . number_format($this->saldo_inicial, 2);
    }

    // Método para obtener saldo conciliado
    public function saldoConciliado()
    {
        $ultimaConciliacion = $this->conciliaciones()
            ->where('estado', 'conciliada')
            ->latest()
            ->first();

        if ($ultimaConciliacion) {
            return $ultimaConciliacion->saldo_libros;
        }

        return $this->saldo_inicial;
    }
}
