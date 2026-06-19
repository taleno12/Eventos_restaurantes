<?php

namespace App\Console\Commands;

use App\Models\Notificacion;
use App\Models\Restaurante;
use App\Models\User;
use Illuminate\Console\Command;

class EnviarNotificacionesVencimiento extends Command
{
    protected $signature   = 'notificaciones:vencimiento';
    protected $description = 'Envía notificaciones de vencimiento de membresía a los restaurantes y al admin';

    public function handle(): void
    {
        $hoy    = now()->startOfDay();
        $admins = User::where('role', 'admin')->get();
        $enviadas = 0;

        $restaurantes = Restaurante::whereNotNull('membresia_vence_en')
            ->with('propietario')
            ->get();

        foreach ($restaurantes as $restaurante) {
            $user = $restaurante->propietario;
            if (!$user) continue;

            $vence     = \Carbon\Carbon::parse($restaurante->membresia_vence_en)->startOfDay();
            $diasResta = (int) $hoy->diffInDays($vence, false);

            // ── Config para el RESTAURANTE ────────────────────────
            $configRestaurante = match (true) {
                $diasResta === 7 => [
                    'tipo'    => 'vencimiento_7',
                    'titulo'  => '⚠️ Tu membresía vence en 7 días',
                    'mensaje' => "Tu membresía del plan {$restaurante->membresia_plan} vence el {$vence->format('d/m/Y')}. Renuévala para seguir disfrutando de todos los beneficios de GastroNicaragua.",
                    'icono'   => 'bi-clock-history',
                    'color'   => 'orange',
                    'url'     => '/mi-restaurante/soporte',
                ],
                $diasResta === 3 => [
                    'tipo'    => 'vencimiento_3',
                    'titulo'  => '🔴 Tu membresía vence en 3 días',
                    'mensaje' => "Solo te quedan 3 días. Tu membresía del plan {$restaurante->membresia_plan} vence el {$vence->format('d/m/Y')}. Contáctanos para renovar y evitar la suspensión de tu cuenta.",
                    'icono'   => 'bi-exclamation-triangle-fill',
                    'color'   => 'red',
                    'url'     => '/mi-restaurante/soporte',
                ],
                $diasResta === 0 => [
                    'tipo'    => 'vencimiento_hoy',
                    'titulo'  => '🚨 Tu membresía vence HOY',
                    'mensaje' => "Tu membresía del plan {$restaurante->membresia_plan} vence hoy. Contáctanos de inmediato al +505 8540 6068 para renovar y evitar que tu restaurante sea suspendido.",
                    'icono'   => 'bi-x-circle-fill',
                    'color'   => 'red',
                    'url'     => '/mi-restaurante/soporte',
                ],
                default => null,
            };

            if (!$configRestaurante) continue;

            // ── Config para el ADMIN ──────────────────────────────
            $configAdmin = match (true) {
                $diasResta === 7 => [
                    'tipo'    => 'admin_vencimiento_7',
                    'titulo'  => "⚠️ {$restaurante->nombre} vence en 7 días",
                    'mensaje' => "El restaurante {$restaurante->nombre} (plan {$restaurante->membresia_plan}) vence el {$vence->format('d/m/Y')}. Considera contactar al propietario para renovar.",
                    'icono'   => 'bi-clock-history',
                    'color'   => 'orange',
                    'url'     => '/contratos',
                ],
                $diasResta === 3 => [
                    'tipo'    => 'admin_vencimiento_3',
                    'titulo'  => "🔴 {$restaurante->nombre} vence en 3 días",
                    'mensaje' => "El restaurante {$restaurante->nombre} (plan {$restaurante->membresia_plan}) vence el {$vence->format('d/m/Y')}. Se recomienda contactar al propietario a la brevedad.",
                    'icono'   => 'bi-exclamation-triangle-fill',
                    'color'   => 'red',
                    'url'     => '/contratos',
                ],
                $diasResta === 0 => [
                    'tipo'    => 'admin_vencimiento_hoy',
                    'titulo'  => "🚨 {$restaurante->nombre} vence HOY",
                    'mensaje' => "El restaurante {$restaurante->nombre} (plan {$restaurante->membresia_plan}) vence hoy. Contacta al propietario de inmediato para renovar o suspender la cuenta.",
                    'icono'   => 'bi-x-circle-fill',
                    'color'   => 'red',
                    'url'     => '/contratos',
                ],
                default => null,
            };

            // ── Notificación al RESTAURANTE ───────────────────────
            $yaExisteRestaurante = Notificacion::where('user_id', $user->id)
                ->where('tipo', $configRestaurante['tipo'])
                ->whereDate('created_at', today())
                ->exists();

            if (!$yaExisteRestaurante) {
                Notificacion::create([
                    'user_id' => $user->id,
                    'tipo'    => $configRestaurante['tipo'],
                    'titulo'  => $configRestaurante['titulo'],
                    'mensaje' => $configRestaurante['mensaje'],
                    'icono'   => $configRestaurante['icono'],
                    'color'   => $configRestaurante['color'],
                    'url'     => $configRestaurante['url'],
                    'leida'   => false,
                ]);
                $enviadas++;
                $this->info("✓ Notificación enviada al restaurante: {$restaurante->nombre} ({$diasResta} días)");
            }

            // ── Notificación a cada ADMIN ─────────────────────────
            if ($configAdmin) {
                foreach ($admins as $admin) {
                    $yaExisteAdmin = Notificacion::where('user_id', $admin->id)
                        ->where('tipo', $configAdmin['tipo'])
                        ->where('mensaje', 'like', "%{$restaurante->nombre}%")
                        ->whereDate('created_at', today())
                        ->exists();

                    if (!$yaExisteAdmin) {
                        Notificacion::create([
                            'user_id' => $admin->id,
                            'tipo'    => $configAdmin['tipo'],
                            'titulo'  => $configAdmin['titulo'],
                            'mensaje' => $configAdmin['mensaje'],
                            'icono'   => $configAdmin['icono'],
                            'color'   => $configAdmin['color'],
                            'url'     => $configAdmin['url'],
                            'leida'   => false,
                        ]);
                        $enviadas++;
                        $this->info("✓ Notificación enviada al admin: {$admin->name} sobre {$restaurante->nombre}");
                    }
                }
            }
        }

        $this->info("Total notificaciones enviadas: {$enviadas}");
    }
}
