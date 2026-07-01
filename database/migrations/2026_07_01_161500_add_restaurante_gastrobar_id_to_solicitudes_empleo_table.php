<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes_empleo', function (Blueprint $table) {
            $table->foreignId('restaurante_id')->nullable()->after('empleo_id')
                ->constrained('restaurantes')->nullOnDelete();
            $table->foreignId('gastrobar_id')->nullable()->after('restaurante_id')
                ->constrained('gastrobares')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes_empleo', function (Blueprint $table) {
            $table->dropForeign(['restaurante_id']);
            $table->dropForeign(['gastrobar_id']);
            $table->dropColumn(['restaurante_id', 'gastrobar_id']);
        });
    }
};
