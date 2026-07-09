<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidentes_pedido', function (Blueprint $table) {
            $table->id();
            $table->morphs('pedido'); // pedido_id + pedido_type (Pedido o PedidoGastrobar)
            $table->foreignId('reportado_por')->constrained('users');
            $table->enum('tipo', ['cliente', 'negocio', 'motorizado'])
                  ->comment('Quien reporta el incidente');
            $table->text('descripcion');
            $table->enum('estado', ['abierto', 'en_revision', 'resuelto'])
                  ->default('abierto');
            $table->text('resolucion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidentes_pedido');
    }
};
