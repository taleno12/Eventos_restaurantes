<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NegociacionPedido extends Model
{
    protected $table = 'negociaciones_pedido';

    protected $fillable = [
        'pedido_type',
        'pedido_id',
        'motorizado_id',
        'iniciado_por_id',
        'estado',
        'tarifa_propuesta_dueno',
        'tarifa_propuesta_motorizado',
        'tarifa_acordada',
        'aceptado_dueno',
        'aceptado_motorizado',
    ];

    protected $casts = [
        'aceptado_dueno'      => 'boolean',
        'aceptado_motorizado' => 'boolean',
        'tarifa_propuesta_dueno'      => 'decimal:2',
        'tarifa_propuesta_motorizado' => 'decimal:2',
        'tarifa_acordada'             => 'decimal:2',
    ];

    public function pedido(): MorphTo
    {
        return $this->morphTo();
    }

    public function motorizado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'motorizado_id');
    }

    public function iniciadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'iniciado_por_id');
    }

    public function mensajes(): HasMany
    {
        return $this->hasMany(MensajeNegociacion::class, 'negociacion_id');
    }

    public function verificarAcuerdo(): bool
    {
        if ($this->aceptado_dueno && $this->aceptado_motorizado) {
            $this->estado = 'aceptado';
            $this->tarifa_acordada = $this->tarifa_propuesta_motorizado ?? $this->tarifa_propuesta_dueno;
            $this->save();
            return true;
        }
        return false;
    }
}
