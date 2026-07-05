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
        Schema::create('mensajes_negociacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('negociacion_id')->constrained('negociaciones_pedido')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('mensaje')->nullable();
            $table->decimal('tarifa_propuesta', 8, 2)->nullable(); // si el mensaje incluye una propuesta de tarifa
            $table->boolean('leido')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes_negociacion');
    }
};
