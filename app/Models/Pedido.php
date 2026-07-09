<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Pedido extends Model
{
    protected $fillable = [
        'restaurante_id',
        'user_id',
        'motorizado_id',
        'estado',
        'total',
        'notas',
        'tipo',
        'direccion_entrega',
        'costo_envio',
    ];

    // Colores y etiquetas para cada estado
    const ESTADOS = [
        'pendiente'      => ['label' => 'Pendiente',      'color' => '#f59e0b'],
        'confirmado'     => ['label' => 'Confirmado',     'color' => '#3b82f6'],
        'en_preparacion' => ['label' => 'En preparación', 'color' => '#8b5cf6'],
        'listo'          => ['label' => 'Listo',          'color' => '#22c55e'],
        'entregado'      => ['label' => 'Entregado',      'color' => '#6b7280'],
        'cancelado'      => ['label' => 'Cancelado',      'color' => '#ef4444'],
        'incidente'      => ['label' => 'Incidente reportado', 'color' => '#dc2626'],
    ];

    public function restaurante(): BelongsTo
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function motorizado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'motorizado_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function negociaciones(): MorphMany
    {
        return $this->morphMany(NegociacionPedido::class, 'pedido');
    }

    public function incidentes(): MorphMany
    {
        return $this->morphMany(IncidentePedido::class, 'pedido');
    }

    public function getEstadoInfoAttribute(): array
    {
        return self::ESTADOS[$this->estado] ?? ['label' => $this->estado, 'color' => '#6b7280'];
    }

    // ✅ AGREGADO: total final a cobrar al cliente (comida + envío), para
    // que el motorizado vea el monto completo en la pantalla de entrega.
    public function getTotalConEnvioAttribute(): float
    {
        return (float) $this->total + (float) ($this->costo_envio ?? 0);
    }
}
