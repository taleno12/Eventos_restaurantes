<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoItem extends Model
{
    protected $fillable = [
        'pedido_id',
        'plato_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'notas',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal'        => 'decimal:2',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function plato(): BelongsTo
    {
        return $this->belongsTo(Plato::class);
    }
}