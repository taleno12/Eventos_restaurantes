<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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