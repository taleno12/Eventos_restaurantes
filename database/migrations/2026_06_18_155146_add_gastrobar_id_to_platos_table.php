<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('platos', function (Blueprint $table) {
            if (!Schema::hasColumn('platos', 'gastrobar_id')) {
                $table->foreignId('gastrobar_id')->nullable()->constrained('gastrobares')->nullOnDelete()->after('restaurante_id');
            }
            if (!Schema::hasColumn('platos', 'categoria')) {
                $table->string('categoria', 100)->nullable()->after('categoria_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('platos', function (Blueprint $table) {
            if (Schema::hasColumn('platos', 'gastrobar_id')) {
                $table->dropForeign(['gastrobar_id']);
                $table->dropColumn('gastrobar_id');
            }
            if (Schema::hasColumn('platos', 'categoria')) {
                $table->dropColumn('categoria');
            }
        });
    }
};
