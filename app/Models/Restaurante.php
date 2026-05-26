<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'whatsapp',
        'foto_portada', // IMPORTANTE: Agregado para permitir la asignación masiva de la imagen principal
        'activo',      // Agregado por si manejas estados de activación en el panel
        'direccion', 
        'latitud', 
        'longitud'
    ];

    /**
     * Un restaurante PERTENECE a un departamento
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Un restaurante PERTENECE a un municipio
     */
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    /**
     * Un restaurante puede tener MUCHOS eventos
     */
    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }

    /**
     * Un restaurante puede tener MUCHAS reseñas
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'restaurante_id');
    }

    /**
     * Relación original para la galería de fotos secundarias
     */
    public function fotos(): HasMany
    {
        return $this->hasMany(RestauranteFoto::class, 'restaurante_id');
    }

    /**
     * ALIAS DE COMPATIBILIDAD: Mapea 'imagenes' a 'fotos' de forma transparente.
     * Esto evita que el controlador o la vista rompan el sistema si llaman a "imagenes".
     */
    public function imagenes(): HasMany
    {
        return $this->fotos();
    }

    /**
     * Promedio de calificaciones
     */
    public function getAverageRatingAttribute(): ?float
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : null;
    }

    /**
     * Total de reseñas
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }
}