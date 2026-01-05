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
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('n_cuota')->default(0);
            $table->decimal('pago_total_bs', 8, 2);
            $table->decimal('pago_pendiente_bs', 8, 2)->nullable();
            $table->decimal('descuento_bs', 8, 2)->nullable();
            $table->date('fecha_pago');
            $table->enum('pago_terminado', ['si', 'no'])->default('no');
            $table->unsignedBigInteger('inscripcione_id');
            $table->timestamps();

            $table->foreign('inscripcione_id')->references('id')->on('inscripciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};
