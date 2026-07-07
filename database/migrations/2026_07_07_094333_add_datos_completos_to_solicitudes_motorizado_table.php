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
        Schema::table('solicitudes_motorizado', function (Blueprint $table) {
            $table->string('nombre_completo')->nullable()->after('user_id');
            $table->unsignedTinyInteger('edad')->nullable()->after('nombre_completo');
            $table->string('foto_perfil')->nullable()->after('placa');
            $table->string('foto_licencia')->nullable()->after('foto_perfil');
            $table->string('foto_record_policial')->nullable()->after('foto_licencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitudes_motorizado', function (Blueprint $table) {
            $table->dropColumn(['nombre_completo', 'edad', 'foto_perfil', 'foto_licencia', 'foto_record_policial']);
        });
    }
};
