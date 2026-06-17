<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurantes', function (Blueprint $table) {
            $table->date('membresia_vence_en')->nullable()->after('activo');
            $table->string('membresia_plan', 50)->default('básico')->after('membresia_vence_en');
        });
    }

    public function down(): void
    {
        Schema::table('restaurantes', function (Blueprint $table) {
            $table->dropColumn(['membresia_vence_en', 'membresia_plan']);
        });
    }
};
