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
        Schema::table('detalles', function (Blueprint $table) {
            $table->enum('tipo_pago', ['Efectivo', 'Transferencia', 'Depósito', 'Tarjeta'])
                ->default('Efectivo')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('detalles', function (Blueprint $table) {
            $table->enum('tipo_pago', ['efectivo', 'qr'])
                ->default('efectivo')
                ->change();
        });
    }
};
