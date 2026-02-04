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
        Schema::create('depositos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caja_id')->nullable();
            $table->unsignedBigInteger('cuenta_bancaria_id')->nullable();
            $table->decimal('monto', 12, 2);
            $table->date('fecha_deposito');
            $table->string('comprobante')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('estado')->default('pendiente');

            $table->unsignedBigInteger('trabajadore_cargo_id')->nullable();
            $table->timestamps();

            $table->foreign('caja_id')->references('id')->on('cajas');
            $table->foreign('cuenta_bancaria_id')->references('id')->on('cuentas_bancarias');
            $table->foreign('trabajadore_cargo_id')->references('id')->on('trabajadores_cargos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depositos');
    }
};
