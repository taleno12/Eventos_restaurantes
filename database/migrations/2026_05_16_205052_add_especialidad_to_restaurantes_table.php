<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurantes', function (Blueprint $table) {
            // Cambiado a 'email' que es el nombre estándar en tu base de datos
            $table->string('especialidad', 100)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('restaurantes', function (Blueprint $table) {
            $table->dropColumn('especialidad');
        });
    }
};