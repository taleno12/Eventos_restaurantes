<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $nombre
 * @property int $activo
 * @property string $email
 * @property string|null $especialidad
 * @property string|null $descripcion
 * @property string|null $direccion
 * @property numeric|null $latitud
 * @property numeric|null $longitud
 * @property string|null $foto_portada
 * @property string|null $instagram
 * @property string|null $tiktok
 * @property string|null $facebook
 * @property string|null $whatsapp
 * @property int $departamento_id
 * @property int|null $municipio_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Departamento $departamento
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Evento> $eventos
 * @property-read int|null $eventos_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RestauranteFoto> $fotos
 * @property-read int|null $fotos_count
 * @property-read float|null $average_rating
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RestauranteFoto> $imagenes
 * @property-read int|null $imagenes_count
 * @property-read \App\Models\Municipio|null $municipio
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereDepartamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereEspecialidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereFotoPortada($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereLatitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereLongitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereMunicipioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereTiktok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante whereWhatsapp($value)
 * @mixin \Eloquent
 */
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