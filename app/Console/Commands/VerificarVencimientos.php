<?php

namespace App\Console\Commands;

use App\Models\Contrato;
use App\Models\Notificacion;
use App\Models\Pago;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class VerificarVencimientos extends Command
{
    protected $signature = 'notificaciones:verificar';

    protected $description = 'Revisa contratos por vencer/vencidos y pagos pendientes, generando notificaciones';

    /**
     * Días de anticipación para avisar que un contrato está por vencer.
     */
    protected int $diasAnticipacion = 7;

    public function handle(): int
    {
        $this->verificarContratosPorVencer();
        $this->verificarContratosVencidos();
        $this->verificarPagosPendientes();

        $this->info('Verificación de vencimientos completada.');
        return self::SUCCESS;
    }

    /**
     * Contratos activos que vencen dentro del rango de anticipación.
     */
    protected function verificarContratosPorVencer(): void
    {
        $limite = Carbon::now()->addDays($this->diasAnticipacion)->endOfDay();

        $contratos = Contrato::where('estado', 'activo')
            ->whereBetween('fecha_fin', [Carbon::now()->startOfDay(), $limite])
            ->get();

        foreach ($contratos as $contrato) {
            $existe = Notificacion::where('tipo', 'contrato_por_vencer')
                ->where('contrato_id', $contrato->id)
                ->whereDate('fecha_evento', $contrato->fecha_fin)
                ->exists();

            if ($existe) {
                continue;
            }

            $nombre   = $contrato->establecimiento()?->nombre ?? 'Establecimiento sin nombre';
            $diasRest = (int) Carbon::now()->diffInDays($contrato->fecha_fin, false);

            Notificacion::create([
                'tipo'         => 'contrato_por_vencer',
                'titulo'       => 'Contrato próximo a vencer',
                'mensaje'      => "El contrato #{$contrato->numero_contrato} de \"{$nombre}\" vence en {$diasRest} día(s) (".$contrato->fecha_fin->format('d/m/Y').').',
                'contrato_id'  => $contrato->id,
                'fecha_evento' => $contrato->fecha_fin,
            ]);
        }
    }

    /**
     * Contratos activos cuya fecha_fin ya pasó (y se marcan como vencidos).
     */
    protected function verificarContratosVencidos(): void
    {
        $contratos = Contrato::where('estado', 'activo')
            ->whereDate('fecha_fin', '<', Carbon::now()->startOfDay())
            ->get();

        foreach ($contratos as $contrato) {
            $existe = Notificacion::where('tipo', 'contrato_vencido')
                ->where('contrato_id', $contrato->id)
                ->exists();

            if (! $existe) {
                $nombre = $contrato->establecimiento()?->nombre ?? 'Establecimiento sin nombre';

                Notificacion::create([
                    'tipo'         => 'contrato_vencido',
                    'titulo'       => 'Contrato vencido',
                    'mensaje'      => "El contrato #{$contrato->numero_contrato} de \"{$nombre}\" venció el ".$contrato->fecha_fin->format('d/m/Y').'.',
                    'contrato_id'  => $contrato->id,
                    'fecha_evento' => $contrato->fecha_fin,
                ]);
            }

            // Actualiza el estado del contrato a vencido
            $contrato->update(['estado' => 'vencido']);
        }
    }

    /**
     * Pagos con estado "pendiente" cuyo periodo_fin ya está cerca o pasó.
     */
    protected function verificarPagosPendientes(): void
    {
        $limite = Carbon::now()->addDays($this->diasAnticipacion)->endOfDay();

        $pagos = Pago::where('estado', 'pendiente')
            ->whereNotNull('periodo_fin')
            ->where('periodo_fin', '<=', $limite)
            ->with('contrato')
            ->get();

        foreach ($pagos as $pago) {
            $existe = Notificacion::where('tipo', 'pago_pendiente')
                ->where('pago_id', $pago->id)
                ->exists();

            if ($existe) {
                continue;
            }

            $nombre = $pago->contrato?->establecimiento()?->nombre ?? 'Establecimiento sin nombre';
            $estadoFecha = $pago->periodo_fin->isPast() ? 'venció el' : 'vence el';

            Notificacion::create([
                'tipo'         => 'pago_pendiente',
                'titulo'       => 'Pago pendiente próximo a vencer',
                'mensaje'      => "El pago {$pago->numero_pago} de \"{$nombre}\" está pendiente y {$estadoFecha} ".$pago->periodo_fin->format('d/m/Y').'.',
                'contrato_id'  => $pago->contrato_id,
                'pago_id'      => $pago->id,
                'fecha_evento' => $pago->periodo_fin,
            ]);
        }
    }
}
