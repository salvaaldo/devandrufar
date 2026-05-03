<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('restrict');
            $table->string('lote');
            $table->integer('cantidad');
            $table->date('fecha_vencimiento');
            $table->date('fecha_ingreso')->default(now());
            $table->enum('estado', ['vigente', 'por_vencer', 'vencido'])->default('vigente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario');
    }
};