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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            
            // 1. Imagen opcional para evitar errores de integridad
            $table->string('imagen')->nullable(); 
            
            // 2. Precio con el formato correcto para C$
            $table->decimal('precio', 10, 2)->nullable();

            // 3. CAMPO UNIFICADO PARA EL TEMPORIZADOR
            // Usamos dateTime para que el contador funcione con fecha y hora exactas
            $table->dateTime('fecha_evento');

            // 4. Relaciones (Asegúrate de incluir departamento_id para tu formulario)
            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade');
            $table->foreignId('restaurante_id')->constrained('restaurantes')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};