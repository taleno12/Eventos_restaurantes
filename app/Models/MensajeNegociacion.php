<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MensajeNegociacion extends Model
{
    protected $table = 'mensajes_negociacion';

    protected $fillable = [
        'negociacion_id',
        'user_id',
        'mensaje',
        'tarifa_propuesta',
        'leido',
    ];

    protected $casts = [
        'leido' => 'boolean',
        'tarifa_propuesta' => 'decimal:2',
    ];

    public function negociacion(): BelongsTo
    {
        return $this->belongsTo(NegociacionPedido::class, 'negociacion_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
