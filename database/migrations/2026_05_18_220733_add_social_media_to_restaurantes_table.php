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
        Schema::table('restaurantes', function (Blueprint $table) {
            $table->string('instagram')->nullable()->after('descripcion');
            $table->string('tiktok')->nullable()->after('instagram');
            $table->string('facebook')->nullable()->after('tiktok');
            $table->string('whatsapp')->nullable()->after('facebook');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurantes', function (Blueprint $table) {
            $table->dropColumn(['instagram', 'tiktok', 'facebook', 'whatsapp']);
        });
    }
};