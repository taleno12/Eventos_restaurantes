<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('plato_id')->constrained('platos')->onDelete('cascade');
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 10, 2); // precio al momento del pedido
            $table->decimal('subtotal', 10, 2);
            $table->text('notas')->nullable();          // ej: "sin cebolla"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_items');
    }
};