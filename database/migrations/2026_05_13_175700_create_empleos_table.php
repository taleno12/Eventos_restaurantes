<?php

// =====================================================
// MIGRACIÓN: database/migrations/xxxx_create_empleos_table.php
// =====================================================
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurante_id')->constrained('restaurantes')->onDelete('cascade');
            
            // NUEVOS CAMPOS: Relación geográfica explícita para la vacante laboral
            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade');
            $table->foreignId('municipio_id')->constrained('municipios')->onDelete('cascade');

            $table->string('titulo', 200);
            $table->text('descripcion');
            $table->text('requisitos')->nullable();
            $table->string('tipo_contrato', 50)->nullable(); // Tiempo completo, Medio tiempo, etc.
            $table->decimal('salario', 10, 2)->nullable();   // null = a convenir
            $table->date('fecha_limite')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('empleos');
    }
};