<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleo extends Model
{
    protected $fillable = [
        'restaurante_id',
        'gastrobar_id',
        'departamento_id',
        'municipio_id',
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

    public function gastrobar(): BelongsTo
    {
        return $this->belongsTo(Gastrobar::class);
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(SolicitudEmpleo::class);
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true)
                     ->where(function ($q) {
                         $q->whereNull('fecha_limite')
                           ->orWhere('fecha_limite', '>=', now());
                     });
    }

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
}
