<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatoOpcionValor extends Model
{
    protected $table = 'plato_opcion_valores';

    protected $fillable = [
        'opcion_id',
        'valor',
        'precio_extra',
        'orden',
    ];

    protected $casts = [
        'precio_extra' => 'decimal:2',
    ];

    public function opcion(): BelongsTo
    {
        return $this->belongsTo(PlatoOpcion::class, 'opcion_id');
    }
}
