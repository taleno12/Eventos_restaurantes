<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use App\Models\PedidoGastrobar;
use Illuminate\Console\Command;

class LimpiarPedidosAntiguos extends Command
{
    /**
     * Pensado para correr automáticamente todos los días vía el scheduler
     * (routes/console.php). No pide confirmación porque corre desapercibido
     * por cron. Para limpiezas manuales puntuales seguí usando
     * pedidos:limpiar-cancelados y pedidos:limpiar-entregados.
     *
     * php artisan pedidos:limpiar-antiguos
     * php artisan pedidos:limpiar-antiguos --dias=15
     * php artisan pedidos:limpiar-antiguos --dry-run
     */
    protected $signature = 'pedidos:limpiar-antiguos {--dias=30 : Días de antigüedad antes de eliminar} {--dry-run : Solo mostrar cuántos se eliminarían, sin borrar}';

    protected $description = 'Elimina automáticamente pedidos (restaurante y gastrobar) cancelados o entregados con más de X días desde su último cambio de estado';

    private const ESTADOS_A_LIMPIAR = ['cancelado', 'entregado'];

    public function handle(): int
    {
        $dias   = (int) $this->option('dias');
        $dryRun = $this->option('dry-run');
        $limite = now()->subDays($dias);

        $pedidosRest = Pedido::whereIn('estado', self::ESTADOS_A_LIMPIAR)
            ->where('updated_at', '<', $limite)
            ->get();

        $pedidosGast = PedidoGastrobar::whereIn('estado', self::ESTADOS_A_LIMPIAR)
            ->where('updated_at', '<', $limite)
            ->get();

        $this->info("Pedidos de restaurante a eliminar: {$pedidosRest->count()}");
        $this->info("Pedidos de gastrobar a eliminar:   {$pedidosGast->count()}");

        if ($dryRun) {
            $this->warn('Modo --dry-run: no se eliminó nada todavía.');
            return self::SUCCESS;
        }

        if ($pedidosRest->isEmpty() && $pedidosGast->isEmpty()) {
            $this->info('No hay nada que limpiar.');
            return self::SUCCESS;
        }

        foreach ($pedidosRest as $pedido) {
            $pedido->items()->delete();
            $pedido->delete();
        }

        foreach ($pedidosGast as $pedido) {
            $pedido->items()->delete();
            $pedido->delete();
        }

        $this->info("✔ Eliminados {$pedidosRest->count()} pedidos de restaurante y {$pedidosGast->count()} de gastrobar (más de {$dias} días).");

        \Log::info("pedidos:limpiar-antiguos → {$pedidosRest->count()} restaurante(s), {$pedidosGast->count()} gastrobar(es) eliminados automáticamente.");

        return self::SUCCESS;
    }
}
