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
        Schema::create('cuentas_bancarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursale_id')->constrained('sucursales');
            $table->foreignId('banco_id')->constrained('bancos');
            $table->string('numero_cuenta');
            $table->enum('tipo_cuenta', ['ahorro', 'corriente', 'moneda_extranjera']);
            $table->enum('moneda', ['BS', 'USD']);
            $table->text('descripcion')->nullable();
            $table->boolean('activa')->default(true);
            $table->decimal('saldo_inicial', 10, 2)->default(0);
            $table->decimal('saldo_actual', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas_bancarias');
    }
};
