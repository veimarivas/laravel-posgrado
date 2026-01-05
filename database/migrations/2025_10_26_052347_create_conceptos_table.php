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
        Schema::create('conceptos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('n_cuotas')->default(1);
            $table->decimal('monto_bs', 8, 2);
            $table->unsignedBigInteger('planes_pago_id');
            $table->timestamps();

            $table->foreign('planes_pago_id')->references('id')->on('planes_pagos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conceptos');
    }
};
