<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pregunta_seguridad')->nullable()->after('telefono');
            $table->string('respuesta_seguridad')->nullable()->after('pregunta_seguridad');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pregunta_seguridad', 'respuesta_seguridad']);
        });
    }
};
