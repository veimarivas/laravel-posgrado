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
        Schema::create('movimientos_cajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caja_id')->nullable();
            $table->string('tipo_movimiento');
            $table->decimal('monto', 12, 2);
            $table->decimal('saldo_anterior', 12, 2);
            $table->decimal('saldo_posterior', 12, 2);
            $table->text('descripcion');
            $table->morphs('referencia');
            $table->unsignedBigInteger('trabajadore_cargo_id')->nullable();
            $table->timestamps();

            $table->foreign('caja_id')->references('id')->on('cajas');
            $table->foreign('trabajadore_cargo_id')->references('id')->on('trabajadores_cargos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_cajas');
    }
};
