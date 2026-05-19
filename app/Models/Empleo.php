<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class Empleo extends Model
{
    protected $fillable = [
        'restaurante_id',
        'departamento_id', // Añadido para la ubicación de la vacante
        'municipio_id',    // Añadido para la ubicación de la vacante
        'titulo',
        'descripcion',
        'requisitos',
        'tipo_contrato',
        'salario',
        'fecha_limite',
        'activo',
    ];
 
    protected $casts = [
        'activo'       => 'boolean',
        'fecha_limite' => 'date',
        'salario'      => 'decimal:2',
    ];
 
    public function restaurante(): BelongsTo
    {
        return $this->belongsTo(Restaurante::class);
    }

    // NUEVA RELACIÓN: Un empleo pertenece a un departamento específico
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    // NUEVA RELACIÓN: Un empleo pertenece a un municipio específico
    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }
 
    // Scope para ofertas activas (útil en la vista pública)
    public function scopeActivas($query)
    {
        return $query->where('activo', true)
                     ->where(function ($q) {
                         $q->whereNull('fecha_limite')
                           ->orWhere('fecha_limite', '>=', now());
                     });
    }
}