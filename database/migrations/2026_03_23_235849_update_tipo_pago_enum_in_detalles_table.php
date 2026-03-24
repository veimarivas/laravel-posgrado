<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE detalles MODIFY COLUMN tipo_pago ENUM('Efectivo','Transferencia','Depósito','Tarjeta') NOT NULL DEFAULT 'Efectivo'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE detalles MODIFY COLUMN tipo_pago ENUM('efectivo','qr') NOT NULL DEFAULT 'efectivo'");
    }
};
