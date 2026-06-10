<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $fillable = [
        'contrato_id',
        'numero_pago',
        'monto',
        'metodo_pago',
        'referencia',
        'fecha_pago',
        'periodo_inicio',
        'periodo_fin',
        'estado',
        'notas',
        'registrado_por',
    ];

    protected $casts = [
        'fecha_pago'     => 'date',
        'periodo_inicio' => 'date',
        'periodo_fin'    => 'date',
        'monto'          => 'decimal:2',
    ];

    // ── RELACIONES ────────────────────────────────────────────────

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    // ── SCOPES ────────────────────────────────────────────────────

    public function scopePagados($query)
    {
        return $query->where('estado', 'pagado');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAnulados($query)
    {
        return $query->where('estado', 'anulado');
    }

    // ── HELPERS ───────────────────────────────────────────────────

    /**
     * Genera el siguiente número de pago correlativo: P-0001, P-0002...
     */
    public static function generarNumeroPago(): string
    {
        $ultimo = static::latest('id')->value('numero_pago');
        $numero = $ultimo ? ((int) substr($ultimo, 2)) + 1 : 1;
        return 'P-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Label legible del método de pago.
     */
    public function getMetodoPagoLabelAttribute(): string
    {
        return match ($this->metodo_pago) {
            'efectivo'      => 'Efectivo',
            'transferencia' => 'Transferencia',
            'tarjeta'       => 'Tarjeta',
            'deposito'      => 'Depósito',
            default         => ucfirst($this->metodo_pago),
        };
    }
}
