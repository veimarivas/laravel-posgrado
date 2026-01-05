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
        Schema::table('ofertas_academicas', function (Blueprint $table) {
            $table->foreignId('fase_id')
                ->nullable() // o ->required() si siempre debe tener una fase
                ->constrained('fases') // asume que la tabla se llama "fases"
                ->onDelete('set null'); // o 'cascade' si quieres eliminar en cadena
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ofertas_academicas', function (Blueprint $table) {
            $table->dropForeign(['fase_id']);
            $table->dropColumn('fase_id');
        });
    }
};
