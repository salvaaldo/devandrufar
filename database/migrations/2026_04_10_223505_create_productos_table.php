<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->foreignId('medicamento_id')->constrained('medicamentos')->onDelete('restrict');
            $table->string('nombre');
            $table->string('forma_farmaceutica')->nullable();
            $table->string('concentracion')->nullable();
            $table->decimal('precio_referencial', 10, 2)->nullable();
            $table->string('origen')->nullable();
            $table->string('marca')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};