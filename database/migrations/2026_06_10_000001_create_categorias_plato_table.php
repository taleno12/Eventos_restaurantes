<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_plato', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurante_id')->constrained('restaurantes')->cascadeOnDelete();
            $table->string('nombre');
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();
        });

        // Agregar FK en platos
        Schema::table('platos', function (Blueprint $table) {
            $table->foreignId('categoria_id')
                  ->nullable()
                  ->after('categoria')
                  ->constrained('categorias_plato')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('platos', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\CategoriaPlato::class);
            $table->dropColumn('categoria_id');
        });

        Schema::dropIfExists('categorias_plato');
    }
};
