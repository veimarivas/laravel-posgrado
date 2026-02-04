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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sucursale_id')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->decimal('saldo_actual', 12, 2)->default(0);
            $table->decimal('saldo_inicial', 12, 2)->default(0);
            $table->string('moneda')->default('BS');
            $table->boolean('activa')->default(true);
            $table->timestamps();

            $table->foreign('sucursale_id')->references('id')->on('sucursales');
            $table->foreign('responsable_id')->references('id')->on('trabajadores_cargos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
