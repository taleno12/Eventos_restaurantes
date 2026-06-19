<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoGastrobarItem extends Model
{
    protected $fillable = [
        'pedido_gastrobar_id',
        'plato_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'notas',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(PedidoGastrobar::class, 'pedido_gastrobar_id');
    }

    public function plato(): BelongsTo
    {
        return $this->belongsTo(Plato::class);
    }
}
