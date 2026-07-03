<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido_gastrobars', function (Blueprint $table) {
            $table->text('direccion_entrega')->nullable()->after('notas');
        });
    }

    public function down(): void
    {
        Schema::table('pedido_gastrobars', function (Blueprint $table) {
            $table->dropColumn('direccion_entrega');
        });
    }
};
