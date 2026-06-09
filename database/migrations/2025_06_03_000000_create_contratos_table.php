<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_contrato')->unique();

            // Relaciones opcionales (solo una estará activa por contrato)

            $table->unsignedBigInteger('gastrobar_id')->nullable();        

            $table->unsignedBigInteger('restaurante_id')->nullable();


            $table->string('representante');
            $table->string('direccion')->nullable();
            $table->enum('plan', ['gratuito', 'basico', 'premium']);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('monto', 10, 2)->default(0);
            $table->enum('forma_pago', ['mensual', 'trimestral', 'anual'])->nullable();
            $table->enum('estado', ['activo', 'vencido', 'pendiente', 'cancelado'])
                  ->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
