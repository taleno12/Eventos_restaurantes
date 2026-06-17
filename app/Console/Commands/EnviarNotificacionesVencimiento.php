<?php

namespace App\Console\Commands;

use App\Models\Notificacion;
use App\Models\Restaurante;
use Illuminate\Console\Command;

class EnviarNotificacionesVencimiento extends Command
{
    protected $signature   = 'notificaciones:vencimiento';
    protected $description = 'Envía notificaciones de vencimiento de membresía a los restaurantes';

    public function handle(): void
    {
        $hoy = now()->startOfDay();

        $restaurantes = Restaurante::whereNotNull('membresia_vence_en')
            ->with('propietario')
            ->get();

        $enviadas = 0;

        foreach ($restaurantes as $restaurante) {
            $user = $restaurante->propietario;
            if (!$user) continue;

            $vence     = \Carbon\Carbon::parse($restaurante->membresia_vence_en)->startOfDay();
            $diasResta = $hoy->diffInDays($vence, false); // negativo si ya venció

            // Definir qué notificación enviar según días restantes
            $config = match (true) {
                $diasResta === 7  => [
                    'tipo'   => 'vencimiento_7',
                    'titulo' => '⚠️ Tu membresía vence en 7 días',
                    'mensaje' => "Tu membresía del plan {$restaurante->membresia_plan} vence el {$vence->format('d/m/Y')}. Renuévala para seguir disfrutando de todos los beneficios de GastroNicaragua.",
                    'icono'  => 'bi-clock-history',
                    'color'  => 'orange',
                ],
                $diasResta === 3  => [
                    'tipo'   => 'vencimiento_3',
                    'titulo' => '🔴 Tu membresía vence en 3 días',
                    'mensaje' => "Solo te quedan 3 días. Tu membresía del plan {$restaurante->membresia_plan} vence el {$vence->format('d/m/Y')}. Contáctanos para renovar y evitar la suspensión de tu cuenta.",
                    'icono'  => 'bi-exclamation-triangle-fill',
                    'color'  => 'red',
                ],
                $diasResta === 0  => [
                    'tipo'   => 'vencimiento_hoy',
                    'titulo' => '🚨 Tu membresía vence HOY',
                    'mensaje' => "Tu membresía del plan {$restaurante->membresia_plan} vence hoy. Contáctanos de inmediato al +505 8540 6068 para renovar y evitar que tu restaurante sea suspendido.",
                    'icono'  => 'bi-x-circle-fill',
                    'color'  => 'red',
                ],
                default => null,
            };

            if (!$config) continue;

            // Evitar duplicados: no crear si ya existe una del mismo tipo hoy
            $yaExiste = Notificacion::where('user_id', $user->id)
                ->where('tipo', $config['tipo'])
                ->whereDate('created_at', today())
                ->exists();

            if ($yaExiste) continue;

            Notificacion::create([
                'user_id' => $user->id,
                'tipo'    => $config['tipo'],
                'titulo'  => $config['titulo'],
                'mensaje' => $config['mensaje'],
                'icono'   => $config['icono'],
                'color'   => $config['color'],
                'url'     => '/mi-restaurante/soporte',
                'leida'   => false,
            ]);

            $enviadas++;
            $this->info("✓ Notificación enviada a: {$restaurante->nombre} ({$diasResta} días)");
        }

        $this->info("Total notificaciones enviadas: {$enviadas}");
    }
}
