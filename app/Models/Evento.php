<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'gastrobar_id',
        'is_destacado',
        'visible_publico',
    ];

    protected $casts = [
        'visible_publico' => 'boolean',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function gastrobar()
    {
        return $this->belongsTo(Gastrobar::class);
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

    /**
     * Solo eventos cuyo restaurante o gastrobar esté activo (no desactivado por el admin)
     */
    public function scopeDeEntidadesActivas($query)
    {
        return $query->where(function ($q) {
            $q->whereHas('restaurante', function ($sub) {
                $sub->where('activo', true);
            })->orWhereHas('gastrobar', function ($sub) {
                $sub->where('activo', true);
            });
        });
    }

    /**
     * Solo eventos que el dueño eligió mostrar al público
     */
    public function scopeVisiblesPublico($query)
    {
        return $query->where('visible_publico', true);
    }
}
