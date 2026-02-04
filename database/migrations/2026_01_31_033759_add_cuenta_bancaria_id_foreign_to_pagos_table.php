<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            // Si la columna no existe, créala como nullable
            $table->unsignedBigInteger('cuenta_bancaria_id')->nullable()->after('recibo');

            // Agrega la llave foránea permitiendo valores nulos
            $table->foreign('cuenta_bancaria_id')
                ->references('id')
                ->on('cuentas_bancarias')
                ->onUpdate('cascade')
                ->onDelete('set null'); // Esto permite que el campo sea NULL si se elimina la cuenta bancaria
        });
    }


    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['cuenta_bancaria_id']);
        });
    }
};
