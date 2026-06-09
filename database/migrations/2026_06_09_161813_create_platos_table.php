<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurante_id')->constrained('restaurantes')->onDelete('cascade');
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->string('imagen')->nullable();
            $table->string('categoria', 80)->nullable(); // Entradas, Platos fuertes, Bebidas, Postres...
            $table->boolean('activo')->default(true);
            $table->unsignedInteger('orden')->default(0); // para ordenar platos en el menú
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platos');
    }
};