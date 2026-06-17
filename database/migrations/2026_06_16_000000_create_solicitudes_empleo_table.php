<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_empleo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleo_id')->constrained('empleos')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email');
            $table->string('telefono');
            $table->unsignedTinyInteger('edad');
            $table->string('municipio');
            $table->text('experiencia')->nullable();
            $table->json('disponibilidad')->nullable();
            $table->string('curriculum')->nullable(); // ruta storage
            $table->text('mensaje')->nullable();
            $table->enum('estado', ['nueva', 'vista', 'contactado', 'descartado'])->default('nueva');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_empleo');
    }
};
