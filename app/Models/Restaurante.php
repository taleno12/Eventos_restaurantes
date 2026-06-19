<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
 * @property array|null $dias_laborales
 * @property string|null $hora_apertura
 * @property string|null $hora_cierre
 * @property int $departamento_id
 * @property int|null $municipio_id
 * @property string|null $membresia_vence_en
 * @property string|null $membresia_plan
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
 * @property-read \App\Models\User|null $propietario
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Restaurante activos()
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
        'telefono',
        'foto_portada',
        'activo',
        'direccion',
        'latitud',
        'longitud',
        'dias_laborales',
        'hora_apertura',
        'hora_cierre',
        'membresia_vence_en',
        'membresia_plan',
    ];

    protected $casts = [
        'dias_laborales' => 'array',
        'activo' => 'boolean',
        'membresia_vence_en' => 'date',
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
     * Un restaurante TIENE UN propietario (usuario con rol 'restaurante')
     */
    public function propietario(): HasOne
    {
        return $this->hasOne(User::class, 'restaurante_id')->where('role', 'restaurante');
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

    public function platos(): HasMany
    {
        return $this->hasMany(Plato::class);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    /**
     * Un restaurante puede tener MUCHAS categorías de platos
     */
    public function categoriasPlato(): HasMany
    {
        return $this->hasMany(\App\Models\CategoriaPlato::class, 'restaurante_id');
    }

    /**
     * Solo restaurantes activos (no desactivados por el admin)
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
