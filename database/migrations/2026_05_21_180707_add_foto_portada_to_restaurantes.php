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
        Schema::table('restaurantes', function (Blueprint $table) {
            // Añadimos la columna para la imagen principal después de 'descripcion'
            $table->string('foto_portada')->nullable()->after('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurantes', function (Blueprint $table) {
            // Eliminamos la columna si revertimos la migración
            $table->dropColumn('foto_portada');
        });
    }
};