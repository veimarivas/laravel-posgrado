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
            $table->unsignedBigInteger('responsable_marketing_cargo_id')->nullable()->after('certificado');
            $table->unsignedBigInteger('responsable_academico_cargo_id')->nullable()->after('responsable_marketing_cargo_id');

            $table->foreign('responsable_marketing_cargo_id')
                ->references('id')->on('trabajadores_cargos');

            $table->foreign('responsable_academico_cargo_id')
                ->references('id')->on('trabajadores_cargos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ofertas_academicas', function (Blueprint $table) {
            //
        });
    }
};
