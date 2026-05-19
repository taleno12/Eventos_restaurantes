<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurante extends Model
{
    // Esto permite que podamos guardar datos masivamente (Se agregan municipio_id y especialidad)
    protected $fillable = [
        'nombre', 
        'email', 
        'especialidad', // <-- Campo añadido para habilitar filtros por tipo de comida
        'descripcion', 
        'departamento_id', 
        'municipio_id',
        'instagram',    // <-- Nuevo: Enlace de Instagram
        'tiktok',       // <-- Nuevo: Enlace de TikTok
        'facebook',     // <-- Nuevo: Enlace de Facebook
        'whatsapp'      // <-- Nuevo: Número o enlace de WhatsApp
    ];

    // Un restaurante PERTENECE a un departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    // Un restaurante PERTENECE a un municipio (Nueva relación geográfica)
    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    // Un restaurante puede tener MUCHOS eventos (anuncios)
    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}