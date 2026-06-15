<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // contrato_por_vencer, contrato_vencido, pago_pendiente
            $table->string('titulo');
            $table->text('mensaje');
            $table->foreignId('contrato_id')->nullable()->constrained('contratos')->onDelete('cascade');
            $table->foreignId('pago_id')->nullable()->constrained('pagos')->onDelete('cascade');
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha_evento')->nullable(); // fecha de vencimiento referida
            $table->timestamps();

            $table->index(['tipo', 'leida']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
