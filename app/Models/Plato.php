<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plato extends Model
{
    protected $fillable = [
        'restaurante_id',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'categoria_id',
        'activo',
        'orden',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio' => 'decimal:2',
    ];

    public function restaurante(): BelongsTo
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function categoriaPlato(): BelongsTo
    {
        return $this->belongsTo(CategoriaPlato::class, 'categoria_id');
    }

    public function pedidoItems(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
