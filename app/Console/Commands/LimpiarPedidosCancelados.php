<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use App\Models\PedidoGastrobar;
use Illuminate\Console\Command;

class LimpiarPedidosCancelados extends Command
{
    /**
     * php artisan pedidos:limpiar-cancelados
     * php artisan pedidos:limpiar-cancelados --dry-run   (solo muestra cuántos hay, no borra nada)
     */
    protected $signature = 'pedidos:limpiar-cancelados {--dry-run : Solo mostrar cuántos se eliminarían, sin borrar}';

    protected $description = 'Elimina permanentemente los pedidos (restaurante y gastrobar) que quedaron con estado "cancelado" antes de la corrección que los borra automáticamente.';

    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $pedidosRestaurante = Pedido::where('estado', 'cancelado')->get();
        $pedidosGastrobar   = PedidoGastrobar::where('estado', 'cancelado')->get();

        $this->info("Pedidos de restaurante cancelados encontrados: {$pedidosRestaurante->count()}");
        $this->info("Pedidos de gastrobar cancelados encontrados:   {$pedidosGastrobar->count()}");

        if ($dryRun) {
            $this->warn('Modo --dry-run: no se eliminó nada todavía.');
            return self::SUCCESS;
        }

        if ($pedidosRestaurante->isEmpty() && $pedidosGastrobar->isEmpty()) {
            $this->info('No hay nada que limpiar. Todo está al día.');
            return self::SUCCESS;
        }

        if (!$this->confirm('¿Confirmás que querés eliminar estos pedidos cancelados de forma permanente?', true)) {
            $this->warn('Operación cancelada por el usuario.');
            return self::SUCCESS;
        }

        $totalRestaurante = Pedido::where('estado', 'cancelado')->delete();
        $totalGastrobar   = PedidoGastrobar::where('estado', 'cancelado')->delete();

        $this->info("✔ Eliminados {$totalRestaurante} pedidos de restaurante.");
        $this->info("✔ Eliminados {$totalGastrobar} pedidos de gastrobar.");
        $this->info('Limpieza completada.');

        return self::SUCCESS;
    }
}
