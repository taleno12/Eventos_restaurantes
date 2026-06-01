<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Gastrobar> $gastrobares
 * @property-read int|null $gastrobares_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Municipio> $municipios
 * @property-read int|null $municipios_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Restaurante> $restaurantes
 * @property-read int|null $restaurantes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departamento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Departamento extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     * * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
    ];

    /**
     * Carga automática del conteo de relaciones para optimizar la vista.
     * Esto permite acceder a $departamento->restaurantes_count 
     * y $departamento->municipios_count directamente sin sobrecargar la base de datos.
     * * @var array<int, string>
     */
    protected $withCount = ['restaurantes', 'municipios', 'gastrobares'];

    /**
     * Obtener los municipios asociados al departamento.
     * Relación de Uno a Muchos (Un departamento tiene muchos municipios).
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class);
    }

    /**
     * Obtener los restaurantes asociados al departamento.
     * Relación de Uno a Muchos (Un departamento tiene muchos restaurantes).
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function restaurantes(): HasMany
    {
        return $this->hasMany(Restaurante::class);
    }

    /**
     * Obtener los gastrobares asociados al departamento.
     * Relación de Uno a Muchos (Un departamento tiene muchos gastrobares).
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gastrobares(): HasMany
    {
        return $this->hasMany(Gastrobar::class);
    }
}