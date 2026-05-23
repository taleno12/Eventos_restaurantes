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
        'is_destacado',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    // ← RELACIÓN AGREGADA
    public function imagenes()
    {
        return $this->hasMany(EventoImagen::class);
    }

    public function getPrecioFormateadoAttribute()
    {
        return 'C$ ' . number_format($this->precio, 2);
    }
}