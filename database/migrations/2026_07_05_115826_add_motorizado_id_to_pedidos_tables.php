<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('motorizado_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();
        });

        // OJO: el nombre real de esta tabla es "pedido_gastrobars"
        // (confirmado con php artisan tinker), no "pedidos_gastrobar".
        Schema::table('pedido_gastrobars', function (Blueprint $table) {
            $table->foreignId('motorizado_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('motorizado_id');
        });

        Schema::table('pedido_gastrobars', function (Blueprint $table) {
            $table->dropConstrainedForeignId('motorizado_id');
        });
    }
};
