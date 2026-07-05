<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // El campo 'role' ya existe (restaurante, gastrobar, usuario);
            // 'motorizado' se agrega simplemente como un valor mas que
            // se guarda ahi, no hace falta tocar el enum si es un string.
            $table->boolean('disponible')->default(false)->after('role');
            $table->string('vehiculo')->nullable()->after('disponible');
            $table->string('placa')->nullable()->after('vehiculo');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['disponible', 'vehiculo', 'placa']);
        });
    }
};
