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
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ofertas_academica_id');
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('trabajadores_cargo_id');
            $table->enum('estado', ['Inscrito', 'Pre-Inscrito'])->default('Inscrito');
            $table->date('fecha_registro');
            $table->string('observacion')->nullable();
            $table->timestamps();

            $table->foreign('ofertas_academica_id')->references('id')->on('ofertas_academicas');
            $table->foreign('estudiante_id')->references('id')->on('estudiantes');
            $table->foreign('trabajadores_cargo_id')->references('id')->on('trabajadores_cargos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
