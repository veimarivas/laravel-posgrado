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
        Schema::create('planes_conceptos', function (Blueprint $table) {
            $table->id();
            $table->integer('n_cuotas')->default(1);
            $table->decimal('pago_bs', 8, 2);

            $table->unsignedBigInteger('ofertas_academica_id');
            $table->unsignedBigInteger('planes_pago_id');
            $table->unsignedBigInteger('concepto_id');
            $table->timestamps();

            $table->foreign('ofertas_academica_id')->references('id')->on('ofertas_academicas');
            $table->foreign('planes_pago_id')->references('id')->on('planes_pagos');
            $table->foreign('concepto_id')->references('id')->on('conceptos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes_conceptos');
    }
};
