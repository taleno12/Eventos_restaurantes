<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade');
            $table->string('numero_pago')->unique();          // P-0001, P-0002...
            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', ['efectivo', 'transferencia', 'tarjeta', 'deposito'])
                  ->default('efectivo');
            $table->string('referencia')->nullable();         // número de transferencia, etc.
            $table->date('fecha_pago');
            $table->date('periodo_inicio')->nullable();       // mes que cubre este pago
            $table->date('periodo_fin')->nullable();
            $table->enum('estado', ['pagado', 'pendiente', 'anulado'])->default('pagado');
            $table->text('notas')->nullable();
            $table->foreignId('registrado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
