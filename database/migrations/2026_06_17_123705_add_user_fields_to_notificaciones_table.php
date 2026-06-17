<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')
                ->constrained('users')->onDelete('cascade');
            $table->string('icono', 50)->default('bi-bell-fill')->after('leida');
            $table->string('color', 20)->default('orange')->after('icono');
            $table->string('url', 255)->nullable()->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'icono', 'color', 'url']);
        });
    }
};
