<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('contratos')->where('plan', 'gratuito')->update(['plan' => 'basico']);
        DB::statement("ALTER TABLE contratos MODIFY COLUMN plan ENUM('basico', 'premium') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE contratos MODIFY COLUMN plan ENUM('gratuito', 'basico', 'premium') NOT NULL");
    }
};
