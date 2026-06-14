<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('gastrobar_id')
                  ->nullable()
                  ->after('restaurante_id')
                  ->constrained('gastrobares')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['gastrobar_id']);
            $table->dropColumn('gastrobar_id');
        });
    }
};
