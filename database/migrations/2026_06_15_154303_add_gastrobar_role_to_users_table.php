<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // En PostgreSQL la columna role es varchar, no ENUM
        // El valor 'gastrobar' ya es válido sin modificar el tipo
    }

    public function down(): void
    {
        //
    }
};
