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
        Schema::table('eventos', function (Blueprint $table) {
            // Creamos la columna booleana. 
            // Por defecto será 'false' (0) para que no todos los eventos sean destacados al inicio.
            $table->boolean('es_destacado')->default(false)->after('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            // Eliminamos la columna si se revierte la migración
            $table->dropColumn('es_destacado');
        });
    }
};