<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PlatoOpcionValor; //

class PlatoOpcion extends Model
{
    protected $table = 'plato_opciones';

    protected $fillable = [
        'plato_id',
        'nombre',
        'tipo',
        'requerido',
        'orden',
    ];

    protected $casts = [
        'requerido' => 'boolean',
    ];

    public function plato(): BelongsTo
    {
        return $this->belongsTo(Plato::class);
    }

    public function valores(): HasMany
    {
        return $this->hasMany(PlatoOpcionValor::class, 'opcion_id')->orderBy('orden');
    }
}
