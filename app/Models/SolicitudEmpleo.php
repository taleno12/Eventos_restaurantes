<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudEmpleo extends Model
{
    protected $table = 'solicitudes_empleo';

    protected $fillable = [
        'empleo_id',
        'user_id',
        'restaurante_id',
        'gastrobar_id',
        'nombre',
        'apellido',
        'email',
        'telefono',
        'edad',
        'municipio',
        'experiencia',
        'disponibilidad',
        'curriculum',
        'mensaje',
        'estado',
    ];

    protected $casts = [
        'disponibilidad' => 'array',
    ];

    public function empleo(): BelongsTo
    {
        return $this->belongsTo(Empleo::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }
}
