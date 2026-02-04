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
        Schema::create('conciliaciones_bancarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_bancaria_id')->constrained('cuentas_bancarias');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('saldo_libros', 12, 2);
            $table->decimal('saldo_extracto', 12, 2);
            $table->decimal('diferencia', 12, 2);
            $table->string('estado')->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->foreignId('trabajadore_cargo_id')->constrained('trabajadores_cargos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conciliaciones_bancarias');
    }
};
