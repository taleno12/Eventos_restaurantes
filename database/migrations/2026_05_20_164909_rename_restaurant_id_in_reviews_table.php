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
            
            // Columnas requeridas por tu sistema según el error SQL
            $table->integer('rating'); // El puntaje numérico (ej. 3)
            $table->string('title')->nullable(); // El título de la reseña
            $table->text('body')->nullable(); // El contenido principal del texto
            
            // Mantenemos la columna comentario por si acaso se usa en otra vista
            $table->text('comentario')->nullable();
            
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