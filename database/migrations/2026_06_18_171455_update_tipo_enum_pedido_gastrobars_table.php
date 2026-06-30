<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // En PostgreSQL la columna tipo es varchar, no ENUM
        // El valor 'retiro' ya es válido sin modificar el tipo
    }

    public function down(): void
    {
        //
    }
};
