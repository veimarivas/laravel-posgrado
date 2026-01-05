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
            $table->dropForeign(['responsable_marketing_id']);
            $table->dropForeign(['responsable_academico_id']);
            $table->dropColumn(['responsable_marketing_id', 'responsable_academico_id']);
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
