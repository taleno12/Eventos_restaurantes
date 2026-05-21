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
        Schema::create('restaurante_fotos', function (Blueprint $table) {
            $table->id();
            // Relación con la tabla restaurantes (Clave foránea)
            $table->foreignId('restaurante_id')->constrained('restaurantes')->onDelete('cascade');
            // Columna para almacenar la ruta o path de la imagen en el storage
            $table->string('ruta_foto'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurante_fotos');
    }
};