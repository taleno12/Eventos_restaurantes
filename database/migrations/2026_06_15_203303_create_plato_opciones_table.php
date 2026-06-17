<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plato_opciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plato_id')->constrained('platos')->onDelete('cascade');
            $table->string('nombre');         // Ej: "Tamaño", "Proteína", "Extras"
            $table->enum('tipo', ['radio', 'checkbox']); // radio = elige uno, checkbox = elige varios
            $table->boolean('requerido')->default(false);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plato_opciones');
    }
};
