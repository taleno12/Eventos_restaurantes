<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipio extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada al modelo.
     * Por convención de Laravel es 'municipios'.
     * * @var string
     */
    protected $table = 'municipios';

    /**
     * Los atributos que se pueden asignar masivamente.
     * * @var array<int, string>
     */
    protected $fillable = [
        'departamento_id',
        'nombre',
    ];

    /**
     * Obtener el departamento al que pertenece el municipio.
     * Relación inversa de Uno a Muchos (Muchos municipios pertenecen a un departamento).
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Obtener los restaurantes ubicados en este municipio.
     * Relación de Uno a Muchos (Un municipio puede tener muchos restaurantes).
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function restaurantes(): HasMany
    {
        return $this->hasMany(Restaurante::class);
    }
}