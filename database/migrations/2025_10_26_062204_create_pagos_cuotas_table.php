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
        Schema::create('pagos_cuotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuota_id');
            $table->unsignedBigInteger('pago_id');
            $table->decimal('pago_bs', 8, 2);
            $table->timestamps();

            $table->foreign('cuota_id')->references('id')->on('cuotas');
            $table->foreign('pago_id')->references('id')->on('pagos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_cuotas');
    }
};
