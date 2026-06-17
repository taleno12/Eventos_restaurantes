<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plato_opcion_valores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opcion_id')->constrained('plato_opciones')->onDelete('cascade');
            $table->string('valor');           // Ej: "Individual", "Familiar", "Con papas"
            $table->decimal('precio_extra', 8, 2)->default(0); // Costo adicional (0 si no aplica)
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plato_opcion_valores');
    }
};
