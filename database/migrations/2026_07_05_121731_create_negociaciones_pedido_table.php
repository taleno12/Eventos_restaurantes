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
        Schema::create('negociaciones_pedido', function (Blueprint $table) {
            $table->id();

            // Polimorfico: funciona para pedido_gastrobars y cualquier otra tabla de pedidos futura
            $table->string('pedido_type');
            $table->unsignedBigInteger('pedido_id');

            $table->foreignId('motorizado_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('iniciado_por_id')->constrained('users')->onDelete('cascade'); // dueño que abrio el chat

            $table->enum('estado', ['pendiente', 'aceptado', 'rechazado', 'cancelado'])
                  ->default('pendiente');

            $table->decimal('tarifa_propuesta_dueno', 8, 2)->nullable();
            $table->decimal('tarifa_propuesta_motorizado', 8, 2)->nullable();
            $table->decimal('tarifa_acordada', 8, 2)->nullable();

            $table->boolean('aceptado_dueno')->default(false);
            $table->boolean('aceptado_motorizado')->default(false);

            $table->timestamps();

            $table->index(['pedido_type', 'pedido_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negociaciones_pedido');
    }
};
