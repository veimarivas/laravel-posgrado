<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transferencias_bancarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_origen_id')->constrained('cuentas_bancarias');
            $table->foreignId('cuenta_destino_id')->constrained('cuentas_bancarias');
            $table->decimal('monto', 12, 2);
            $table->string('moneda')->default('BS');
            $table->decimal('tasa_cambio', 10, 4)->nullable();
            $table->date('fecha_transferencia');
            $table->date('fecha_efectiva');
            $table->string('comprobante')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('tipo_transferencia');
            $table->string('estado')->default('pendiente');
            $table->text('motivo_correccion')->nullable();
            $table->foreignId('pago_id')->nullable()->constrained('pagos');
            $table->foreignId('trabajadore_cargo_id')->constrained('trabajadores_cargos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transferencias_bancarias');
    }
};
