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
        // Creamos la tabla reviews desde cero con sus relaciones e índice único
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // Relación con usuarios (asumiendo que tu tabla de usuarios usa la convención nativa de Laravel)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Relación con restaurantes
            $table->foreignId('restaurante_id')->constrained('restaurantes')->onDelete('cascade');
            
            // Columnas típicas de una reseña (puedes añadir o quitar las que necesites)
            $table->text('comentario')->nullable();
            $table->integer('estrellas')->default(5); 
            
            $table->timestamps();

            // Evita que un usuario duplique una reseña en el mismo restaurante
            $table->unique(['restaurante_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};