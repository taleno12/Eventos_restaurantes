<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Primero normalizamos filas existentes que pudieran tener los valores viejos
        DB::table('pedido_gastrobars')->where('tipo', 'delivery')->update(['tipo' => 'envio']);
        DB::table('pedido_gastrobars')->where('tipo', 'para_llevar')->update(['tipo' => 'retiro']);

        // Eliminamos el constraint viejo
        DB::statement('ALTER TABLE pedido_gastrobars DROP CONSTRAINT IF EXISTS pedido_gastrobars_tipo_check');

        // Creamos el nuevo constraint con los valores actuales
        DB::statement("ALTER TABLE pedido_gastrobars ADD CONSTRAINT pedido_gastrobars_tipo_check CHECK (tipo IN ('envio', 'retiro'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE pedido_gastrobars DROP CONSTRAINT IF EXISTS pedido_gastrobars_tipo_check');
        DB::statement("ALTER TABLE pedido_gastrobars ADD CONSTRAINT pedido_gastrobars_tipo_check CHECK (tipo IN ('delivery', 'para_llevar'))");
    }
};
