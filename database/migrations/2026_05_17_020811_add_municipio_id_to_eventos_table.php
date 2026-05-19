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
            // Creamos la columna justo después de departamento_id y la vinculamos como llave foránea
            $table->foreignId('municipio_id')
                  ->nullable()
                  ->after('departamento_id')
                  ->constrained('municipios')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropForeign(['municipio_id']);
            $table->dropColumn('municipio_id');
        });
    }
};