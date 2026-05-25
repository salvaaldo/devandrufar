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
        Schema::table('detecciones', function (Blueprint $table) {
            $table->string('lote')->nullable()->after('fecha_detectada');
        });
    }

    public function down(): void
    {
        Schema::table('detecciones', function (Blueprint $table) {
            $table->dropColumn('lote');
        });
    }
};
