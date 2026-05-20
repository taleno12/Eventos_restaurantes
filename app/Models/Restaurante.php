<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Review;

class Restaurante extends Model
{
    protected $fillable = [
        'nombre', 
        'email', 
        'especialidad',
        'descripcion', 
        'departamento_id', 
        'municipio_id',
        'instagram',
        'tiktok',
        'facebook',
        'whatsapp'
    ];

    // Un restaurante PERTENECE a un departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    // Un restaurante PERTENECE a un municipio
    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    // Un restaurante puede tener MUCHOS eventos
    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }

    // Un restaurante puede tener MUCHAS reseñas
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'restaurante_id');
    }

    // Promedio de calificaciones
    public function getAverageRatingAttribute(): ?float
    {
        return $this->reviews()->avg('rating')
            ? round($this->reviews()->avg('rating'), 1)
            : null;
    }

    // Total de reseñas
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }
}