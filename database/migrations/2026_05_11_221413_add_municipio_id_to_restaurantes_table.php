<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurantes', function (Blueprint $table) {
            // Se agrega la llave foránea del municipio, permitiendo nulo por si ya tienes datos previos
            $table->foreignId('municipio_id')->nullable()->after('departamento_id')->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('restaurantes', function (Blueprint $table) {
            $table->dropForeign(['municipio_id']);
            $table->dropColumn('municipio_id');
        });
    }
};