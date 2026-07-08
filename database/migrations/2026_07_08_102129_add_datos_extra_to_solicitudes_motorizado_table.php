<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes_motorizado', function (Blueprint $table) {
            $table->string('cedula')->nullable()->after('nombre_completo');
            $table->date('fecha_nacimiento')->nullable()->after('cedula');
            $table->string('genero')->nullable()->after('fecha_nacimiento');
            $table->string('contacto')->nullable()->after('genero');
            $table->string('localidad')->nullable()->after('municipio_id');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes_motorizado', function (Blueprint $table) {
            $table->dropColumn(['cedula', 'fecha_nacimiento', 'genero', 'contacto', 'localidad']);
        });
    }
};
