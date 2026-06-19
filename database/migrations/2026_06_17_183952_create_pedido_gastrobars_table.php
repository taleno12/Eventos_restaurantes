<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_gastrobars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gastrobar_id')->constrained('gastrobares')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('estado', ['pendiente','confirmado','en_preparacion','listo','entregado','cancelado'])
                  ->default('pendiente');
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notas')->nullable();
            $table->enum('tipo', ['mesa','para_llevar','delivery'])->default('mesa');
            $table->timestamps();
        });

        Schema::create('pedido_gastrobar_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_gastrobar_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plato_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_gastrobar_items');
        Schema::dropIfExists('pedido_gastrobars');
    }
};
