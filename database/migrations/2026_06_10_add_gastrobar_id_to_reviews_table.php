<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // 1. Soltar la foreign key de restaurante_id primero
            $table->dropForeign(['restaurante_id']);

            // 2. Ahora sí se puede eliminar el unique
            $table->dropUnique(['restaurante_id', 'user_id']);

            // 3. Re-crear la foreign key de restaurante_id
            $table->foreign('restaurante_id')
                  ->references('id')->on('restaurantes')
                  ->onDelete('cascade');

            // 4. Agregar gastrobar_id nullable
            $table->foreignId('gastrobar_id')
                  ->nullable()
                  ->after('restaurante_id')
                  ->constrained('gastrobares')
                  ->onDelete('cascade');

            // 5. Nuevos uniques separados
            $table->unique(['restaurante_id', 'user_id'], 'reviews_restaurante_user_unique');
            $table->unique(['gastrobar_id', 'user_id'], 'reviews_gastrobar_user_unique');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Revertir en orden inverso
            $table->dropUnique('reviews_gastrobar_user_unique');
            $table->dropUnique('reviews_restaurante_user_unique');
            $table->dropForeign(['gastrobar_id']);
            $table->dropColumn('gastrobar_id');
            $table->dropForeign(['restaurante_id']);
            $table->foreign('restaurante_id')
                  ->references('id')->on('restaurantes')
                  ->onDelete('cascade');
            $table->unique(['restaurante_id', 'user_id']);
        });
    }
};
