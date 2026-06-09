<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $restaurante_id
 * @property int|null $gastrobar_id
 * @property int $departamento_id
 * @property int $municipio_id
 * @property string $titulo
 * @property string $descripcion
 * @property string|null $requisitos
 * @property string|null $tipo_contrato
 * @property numeric|null $salario
 * @property \Illuminate\Support\Carbon|null $fecha_limite
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Departamento $departamento
 * @property-read \App\Models\Municipio $municipio
 * @property-read \App\Models\Restaurante|null $restaurante
 * @property-read \App\Models\Gastrobar|null $gastrobar
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo activas()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereDepartamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereFechaLimite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereGastrobarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereMunicipioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereRequisitos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereRestauranteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereSalario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereTipoContrato($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Empleo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Empleo extends Model
{
    protected $fillable = [
        'restaurante_id',
        'gastrobar_id',    // ← nuevo
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

    // ← nueva relación
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
