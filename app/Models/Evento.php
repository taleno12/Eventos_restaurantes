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
        'municipio_id',    // <--- NUEVO: Permitir guardar el municipio del evento
        'restaurante_id',
        'is_destacado',
    ];

    /**
     * Relación: Un evento PERTENECE a un restaurante.
     */
    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    /**
     * Relación: Un evento PERTENECE a un departamento.
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Relación: Un evento PERTENECE a un municipio (NUEVO)
     * (Agregado para que la vista de detalle sepa exactamente en qué zona es)
     */
    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    /**
     * Atributo formateado para el precio (C$)
     */
    public function getPrecioFormateadoAttribute()
    {
        return 'C$ ' . number_format($this->precio, 2);
    }
}