<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notificacion;

class LimpiarNotificacionesAntiguas extends Command
{
    protected $signature   = 'notificaciones:limpiar';
    protected $description = 'Elimina notificaciones con más de 20 días';

    public function handle()
    {
        $eliminadas = Notificacion::where('created_at', '<', now()->subDays(20))->delete();
        $this->info("Se eliminaron {$eliminadas} notificaciones antiguas.");
    }
}
