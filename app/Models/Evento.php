<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $titulo
 * @property string $descripcion
 * @property int $es_destacado
 * @property string|null $imagen
 * @property numeric|null $precio
 * @property string $fecha_evento
 * @property int $departamento_id
 * @property int|null $municipio_id
 * @property int|null $restaurante_id
 * @property int|null $gastrobar_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_destacado
 * @property-read \App\Models\Departamento $departamento
 * @property-read mixed $precio_formateado
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventoImagen> $imagenes
 * @property-read int|null $imagenes_count
 * @property-read \App\Models\Municipio|null $municipio
 * @property-read \App\Models\Restaurante|null $restaurante
 * @property-read \App\Models\Gastrobar|null $gastrobar
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereDepartamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereEsDestacado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereFechaEvento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereGastrobarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereImagen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereIsDestacado($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereMunicipioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento wherePrecio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereRestauranteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Evento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'precio',
        'imagen',
        'fecha_evento',
        'departamento_id',
        'municipio_id',
        'restaurante_id',
        'gastrobar_id',   // ← nuevo
        'is_destacado',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function gastrobar()
    {
        return $this->belongsTo(Gastrobar::class);  // ← nuevo
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function imagenes()
    {
        return $this->hasMany(EventoImagen::class);
    }

    public function getPrecioFormateadoAttribute()
    {
        return 'C$ ' . number_format($this->precio, 2);
    }
}
