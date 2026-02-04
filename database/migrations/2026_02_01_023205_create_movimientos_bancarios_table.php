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
        Schema::create('movimientos_bancarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_bancaria_id')->constrained('cuentas_bancarias');
            $table->string('tipo_movimiento');
            $table->decimal('monto', 12, 2);
            $table->decimal('saldo_anterior', 12, 2);
            $table->decimal('saldo_posterior', 12, 2);
            $table->text('descripcion');
            $table->morphs('referencia');
            $table->foreignId('trabajadore_cargo_id')->constrained('trabajadores_cargos');
            $table->boolean('conciliado')->default(false);
            $table->date('fecha_conciliacion')->nullable();
            $table->foreignId('conciliacion_id')->nullable()->constrained('conciliaciones_bancarias');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_bancarios');
    }
};
