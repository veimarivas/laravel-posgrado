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
        Schema::create('estudios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('persona_id')->nullable();
            $table->unsignedBigInteger('grados_academico_id')->nullable();
            $table->unsignedBigInteger('universidade_id')->nullable();
            $table->unsignedBigInteger('profesione_id')->nullable();
            $table->enum('estado', ['En Desarrollo', 'Concluido'])->default('Concluido');
            $table->string('documento')->nullable();
            $table->timestamps();

            $table->foreign('persona_id')->references('id')->on('personas');
            $table->foreign('grados_academico_id')->references('id')->on('grados_academicos');
            $table->foreign('universidade_id')->references('id')->on('universidades');
            $table->foreign('profesione_id')->references('id')->on('profesiones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudios');
    }
};
