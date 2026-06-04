<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contrato extends Model
{
    protected $fillable = [
        'numero_contrato',
        'gastrobar_id',
        'restaurante_id',
        'representante',
        'direccion',
        'plan',
        'fecha_inicio',
        'fecha_fin',
        'monto',
        'forma_pago',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
        'monto'        => 'decimal:2',
    ];

    // ── RELACIONES ───────────────────────────────────────────────

    public function gastrobar(): BelongsTo
    {
        return $this->belongsTo(Gastrobar::class);
    }

    public function restaurante(): BelongsTo
    {
        return $this->belongsTo(Restaurante::class);
    }

    // ── HELPERS ──────────────────────────────────────────────────

    /**
     * Devuelve el establecimiento activo (gastrobar o restaurante).
     */
    public function establecimiento(): Gastrobar|Restaurante|null
    {
        return $this->gastrobar ?? $this->restaurante;
    }

    /**
     * Devuelve el tipo de establecimiento como string.
     */
    public function tipoEstablecimiento(): string
    {
        return $this->gastrobar_id ? 'gastrobar' : 'restaurante';
    }

    // ── SCOPES ───────────────────────────────────────────────────

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeVencidos($query)
    {
        return $query->where('estado', 'vencido');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }
}
