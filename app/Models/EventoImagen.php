<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $evento_id
 * @property string $ruta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Evento $evento
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventoImagen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventoImagen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventoImagen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventoImagen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventoImagen whereEventoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventoImagen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventoImagen whereRuta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventoImagen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EventoImagen extends Model
{
    protected $table = 'evento_imagenes';

    protected $fillable = ['evento_id', 'ruta'];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }
}