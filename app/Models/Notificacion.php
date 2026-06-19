<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'tipo',
        'titulo',
        'mensaje',
        'contrato_id',
        'pago_id',
        'leida',
        'fecha_evento',
        'user_id',
        'icono',
        'color',
        'url',
    ];

    protected $casts = [
        'leida'        => 'boolean',
        'fecha_evento' => 'datetime',
    ];

    // ── RELACIONES ────────────────────────────────────────────────

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── SCOPES ────────────────────────────────────────────────────

    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    public function scopeDelUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }


    // ── HELPERS ───────────────────────────────────────────────────

    /**
     * Icono Bootstrap según el tipo de notificación.
     */
    public function getIconoAttribute(): string
    {
        return match ($this->tipo) {
            'contrato_por_vencer' => 'bi-hourglass-split',
            'contrato_vencido'    => 'bi-exclamation-triangle-fill',
            'pago_pendiente'      => 'bi-cash-coin',
            'mensaje_admin'       => 'bi-megaphone-fill',
            default               => 'bi-bell',
        };
    }

    /**
     * Color (clase Bootstrap) según el tipo de notificación.
     * Nota: 'warning' se usa para mensaje_admin porque funciona bien
     * tanto en la vista admin (clases Bootstrap) como en la vista del
     * restaurante (cae al estilo naranja por defecto).
     */
    public function getColorAttribute(): string
    {
        return match ($this->tipo) {
            'contrato_por_vencer' => 'warning',
            'contrato_vencido'    => 'danger',
            'pago_pendiente'      => 'info',
            'mensaje_admin'       => 'warning',
            default               => 'secondary',
        };
    }
}
