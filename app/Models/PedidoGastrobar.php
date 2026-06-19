<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PedidoGastrobar extends Model
{
    protected $fillable = [
        'gastrobar_id',
        'user_id',
        'estado',
        'total',
        'notas',
        'tipo',
    ];

    const ESTADOS = [
        'pendiente'      => ['label' => 'Pendiente',      'color' => '#f59e0b'],
        'confirmado'     => ['label' => 'Confirmado',     'color' => '#3b82f6'],
        'en_preparacion' => ['label' => 'En preparación', 'color' => '#8b5cf6'],
        'listo'          => ['label' => 'Listo',          'color' => '#22c55e'],
        'entregado'      => ['label' => 'Entregado',      'color' => '#6b7280'],
        'cancelado'      => ['label' => 'Cancelado',      'color' => '#ef4444'],
    ];

    public function gastrobar(): BelongsTo
    {
        return $this->belongsTo(Gastrobar::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PedidoGastrobarItem::class);
    }

    public function getEstadoInfoAttribute(): array
    {
        return self::ESTADOS[$this->estado] ?? ['label' => $this->estado, 'color' => '#6b7280'];
    }
}
