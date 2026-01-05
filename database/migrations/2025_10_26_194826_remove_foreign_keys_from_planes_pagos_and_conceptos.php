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
        Schema::table('planes_pagos_and_conceptos', function (Blueprint $table) {
            Schema::table('conceptos', function (Blueprint $table) {
                $table->dropForeign(['planes_pago_id']);
                $table->dropColumn('planes_pago_id');
            });

            Schema::table('planes_pagos', function (Blueprint $table) {
                $table->dropForeign(['planes_pago_id']);
                $table->dropColumn('planes_pago_id');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planes_pagos_and_conceptos', function (Blueprint $table) {
            //
        });
    }
};
