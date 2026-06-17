<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','restaurante','usuario','gastrobar') NOT NULL DEFAULT 'usuario'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','restaurante','usuario') NOT NULL DEFAULT 'usuario'");
    }
};
