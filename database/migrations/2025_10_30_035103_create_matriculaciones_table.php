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
        Schema::create('matriculaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modulo_id')->nullable();
            $table->unsignedBigInteger('inscripcione_id')->nullable();
            $table->integer('nota_regular')->default(0);
            $table->integer('nota_nivelacion')->default(0);
            $table->timestamps();

            $table->foreign('modulo_id')->references('id')->on('modulos');
            $table->foreign('inscripcione_id')->references('id')->on('inscripciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculaciones');
    }
};
