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
        Schema::create('sucursales_cuentas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sucursale_id');
            $table->unsignedBigInteger('cuenta_id');
            $table->timestamps();

            $table->foreign('sucursale_id')->references('id')->on('sucursales');
            $table->foreign('cuenta_id')->references('id')->on('cuentas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursales_cuentas');
    }
};
