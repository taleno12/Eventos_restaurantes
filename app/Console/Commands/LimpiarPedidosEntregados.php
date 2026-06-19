<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use App\Models\PedidoGastrobar;
use Illuminate\Console\Command;

class LimpiarPedidosEntregados extends Command
{
    /**
     * php artisan pedidos:limpiar-entregados
     * php artisan pedidos:limpiar-entregados --dry-run   (solo muestra cuántos hay, no borra nada)
     */
    protected $signature = 'pedidos:limpiar-entregados {--dry-run : Solo mostrar cuántos se eliminarían, sin borrar}';

    protected $description = 'Elimina permanentemente los pedidos de PRUEBA (restaurante y gastrobar) que quedaron en estado "entregado". ADVERTENCIA: esto borra datos que cuentan en estadísticas/ingresos.';

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $pedidosRestaurante = Pedido::where('estado', 'entregado')->get();
        $pedidosGastrobar   = PedidoGastrobar::where('estado', 'entregado')->get();

        $this->info("Pedidos de restaurante 'entregado' encontrados: {$pedidosRestaurante->count()}");
        $this->info("Pedidos de gastrobar 'entregado' encontrados:   {$pedidosGastrobar->count()}");

        if ($dryRun) {
            $this->warn('Modo --dry-run: no se eliminó nada todavía.');
            return self::SUCCESS;
        }

        if ($pedidosRestaurante->isEmpty() && $pedidosGastrobar->isEmpty()) {
            $this->info('No hay nada que limpiar.');
            return self::SUCCESS;
        }

        $this->warn('⚠️  ATENCIÓN: esto va a borrar pedidos "entregado" de forma permanente.');
        $this->warn('⚠️  Estos pedidos cuentan normalmente en estadísticas e ingresos históricos.');

        if (!$this->confirm('¿Confirmás que son datos de PRUEBA y querés eliminarlos de forma permanente?', false)) {
            $this->warn('Operación cancelada por el usuario.');
            return self::SUCCESS;
        }

        $totalRestaurante = Pedido::where('estado', 'entregado')->delete();
        $totalGastrobar   = PedidoGastrobar::where('estado', 'entregado')->delete();

        $this->info("✔ Eliminados {$totalRestaurante} pedidos de restaurante.");
        $this->info("✔ Eliminados {$totalGastrobar} pedidos de gastrobar.");
        $this->info('Limpieza completada.');

        return self::SUCCESS;
    }
}
