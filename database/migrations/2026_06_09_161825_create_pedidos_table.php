<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurante_id')->constrained('restaurantes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('estado', [
                'pendiente',
                'confirmado',
                'en_preparacion',
                'listo',
                'entregado',
                'cancelado'
            ])->default('pendiente');
            $table->decimal('total', 10, 2);
            $table->text('notas')->nullable();       // instrucciones especiales del cliente
            $table->string('tipo')->default('mesa'); // mesa, para_llevar, delivery
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};