<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastrobares', function (Blueprint $table) {
            $table->id();

            // Datos generales
            $table->string('nombre', 100);
            $table->string('email')->nullable();
            $table->string('tipo_cocina', 100)->nullable();
            $table->string('tipo_bar', 100)->nullable();  // Cocktail Bar, Sports Bar, Rooftop, Lounge...
            $table->text('descripcion')->nullable();

            // Horarios
            $table->time('hora_apertura')->nullable();
            $table->time('hora_cierre')->nullable();
            $table->json('dias_atencion')->nullable(); // ["lunes","martes","miercoles"...]

            // Ambiente
            $table->string('tipo_musica', 100)->nullable();  // Jazz, Electrónica, En vivo...
            $table->unsignedInteger('capacidad')->nullable();
            $table->string('ambiente', 50)->nullable(); // Interior, Exterior, Rooftop, Mixto

            // Ubicación
            $table->unsignedBigInteger('departamento_id')->nullable();
            $table->unsignedBigInteger('municipio_id')->nullable();
            $table->string('direccion')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();

            // Redes sociales
            $table->string('whatsapp')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();

            // Multimedia
            $table->string('imagen_principal')->nullable();
            $table->json('galeria')->nullable();

            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastrobares');
    }
};