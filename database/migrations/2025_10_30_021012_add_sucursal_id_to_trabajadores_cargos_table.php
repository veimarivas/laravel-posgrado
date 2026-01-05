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
        Schema::table('trabajadores_cargos', function (Blueprint $table) {
            $table->unsignedBigInteger('sucursale_id')->after('id')->nullable();
            $table->foreign('sucursale_id')->references('id')->on('sucursales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabajadores_cargos', function (Blueprint $table) {
            $table->dropForeign(['sucursale_id']);
            $table->dropColumn('sucursale_id');
        });
    }
};
