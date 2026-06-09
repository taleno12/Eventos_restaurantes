<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            // Hacer restaurante_id nullable (antes era NOT NULL)
            $table->unsignedBigInteger('restaurante_id')->nullable()->change();

            // Agregar gastrobar_id
            $table->foreignId('gastrobar_id')
                  ->nullable()
                  ->after('restaurante_id')
                  ->constrained('gastrobares')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropForeign(['gastrobar_id']);
            $table->dropColumn('gastrobar_id');
            $table->unsignedBigInteger('restaurante_id')->nullable(false)->change();
        });
    }
};
