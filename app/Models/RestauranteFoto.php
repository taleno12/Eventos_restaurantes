<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $restaurante_id
 * @property string $ruta_foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Restaurante $restaurante
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestauranteFoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestauranteFoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestauranteFoto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestauranteFoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestauranteFoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestauranteFoto whereRestauranteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestauranteFoto whereRutaFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RestauranteFoto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RestauranteFoto extends Model
{
    // Campos que se pueden llenar de forma masiva
    protected $fillable = [
        'restaurante_id',
        'ruta_foto',
    ];

    /**
     * Obtiene el restaurante al que pertenece la foto.
     */
    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }
}