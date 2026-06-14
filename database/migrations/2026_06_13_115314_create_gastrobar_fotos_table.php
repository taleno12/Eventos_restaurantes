<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastrobar_fotos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gastrobar_id');
            $table->string('ruta_foto');
            $table->timestamps();

            $table->foreign('gastrobar_id')
                  ->references('id')
                  ->on('gastrobares')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastrobar_fotos');
    }
};
