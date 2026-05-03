<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cotizacion_detalles', function (Blueprint $table) {
            // 1. Creamos la columna para el texto del lote
            $table->string('lote')->nullable()->after('producto_id');

            // 2. IMPORTANTE: Hacemos que inventario_id pueda ser nulo
            // (Para cuando escribes el lote a mano y no viene de la DB)
            $table->unsignedBigInteger('inventario_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotizacion_detalles', function (Blueprint $table) {
            //
        });
    }
};
