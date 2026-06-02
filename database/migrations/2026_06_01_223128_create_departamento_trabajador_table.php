<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departamento_trabajador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajador_id')
                  ->constrained('trabajadores')
                  ->onDelete('cascade');
            $table->foreignId('departamento_id')
                  ->constrained('departamentos')
                  ->onDelete('cascade');
            $table->timestamps();

            // Un trabajador no puede estar dos veces en el mismo departamento
            $table->unique(['trabajador_id', 'departamento_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departamento_trabajador');
    }
};
