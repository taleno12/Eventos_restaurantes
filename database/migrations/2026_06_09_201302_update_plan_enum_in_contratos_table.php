<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('contratos')->where('plan', 'gratuito')->update(['plan' => 'basico']);
    }

    public function down(): void
    {
        // No se puede revertir fácilmente en PostgreSQL
    }
};
