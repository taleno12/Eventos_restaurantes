<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class IncidentePedido extends Model
{
    protected $table = 'incidentes_pedido';

    protected $fillable = [
        'pedido_id',
        'pedido_type',
        'reportado_por',
        'tipo',
        'descripcion',
        'estado',
        'resolucion',
    ];

    public function pedido(): MorphTo
    {
        return $this->morphTo();
    }

    public function reportante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reportado_por');
    }
}
