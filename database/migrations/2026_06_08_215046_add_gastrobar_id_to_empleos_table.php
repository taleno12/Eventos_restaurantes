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
        Schema::table('empleos', function (Blueprint $table) {
            // Agregar gastrobar_id nullable después de restaurante_id
            $table->foreignId('gastrobar_id')
                  ->nullable()
                  ->after('restaurante_id')
                  ->constrained('gastrobares')
                  ->nullOnDelete();

            // Hacer restaurante_id nullable porque ahora puede pertenecer a un gastrobar
            $table->unsignedBigInteger('restaurante_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleos', function (Blueprint $table) {
            $table->dropForeign(['gastrobar_id']);
            $table->dropColumn('gastrobar_id');

            // Revertir restaurante_id a no nullable
            $table->unsignedBigInteger('restaurante_id')->nullable(false)->change();
        });
    }
};
