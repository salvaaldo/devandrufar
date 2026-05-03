<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_bajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->string('lote', 50);
            $table->integer('cantidad');
            $table->date('fecha_vencimiento');
            $table->date('fecha_ingreso')->nullable();
            $table->string('motivo', 100)->default('vencido');
            $table->text('observacion')->nullable();
            $table->timestamp('fecha_baja')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_bajas');
    }
};