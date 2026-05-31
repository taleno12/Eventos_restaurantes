<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'restaurante', 'usuario'])
                  ->default('usuario')
                  ->after('email');
            $table->foreignId('restaurante_id')
                  ->nullable()
                  ->constrained('restaurantes')
                  ->onDelete('set null')
                  ->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['restaurante_id']);
            $table->dropColumn(['role', 'restaurante_id']);
        });
    }
};