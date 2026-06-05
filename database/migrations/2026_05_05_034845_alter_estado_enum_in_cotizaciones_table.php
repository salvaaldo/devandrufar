<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE cotizaciones ALTER COLUMN estado TYPE VARCHAR(255)");
        DB::statement("ALTER TABLE cotizaciones ALTER COLUMN estado SET DEFAULT 'borrador'");
        DB::statement("ALTER TABLE cotizaciones ADD CONSTRAINT cotizaciones_estado_check CHECK (estado IN ('borrador', 'enviada', 'aprobada', 'rechazada', 'anulada'))");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE cotizaciones DROP CONSTRAINT cotizaciones_estado_check");
        DB::statement("ALTER TABLE cotizaciones ADD CONSTRAINT cotizaciones_estado_check CHECK (estado IN ('borrador', 'enviada', 'aprobada', 'rechazada'))");
    }
};