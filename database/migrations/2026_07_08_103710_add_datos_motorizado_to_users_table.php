<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cedula')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('genero')->nullable();
            $table->string('contacto')->nullable();
            $table->string('localidad')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cedula', 'fecha_nacimiento', 'genero', 'contacto', 'localidad']);
        });
    }
};
