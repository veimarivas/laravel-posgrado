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
        Schema::table('pagos', function (Blueprint $table) {
            $table->foreignId('caja_id')->nullable()->constrained('cajas');
            $table->foreignId('deposito_id')->nullable()->constrained('depositos');
            $table->string('estado')->default('registrado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['caja_id']);
            $table->dropForeign(['deposito_id']);
            $table->dropColumn(['caja_id', 'deposito_id', 'estado']);
        });
    }
};
