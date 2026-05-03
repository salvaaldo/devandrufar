<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detecciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_detectado')->nullable();
            $table->string('fecha_detectada')->nullable();
            $table->enum('estado', ['VIGENTE', 'PROXIMO', 'VENCIDO', 'DESCONOCIDO'])->default('DESCONOCIDO');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detecciones');
    }
};