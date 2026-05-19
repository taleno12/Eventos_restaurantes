<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     * * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
    ];

    /**
     * Carga automática del conteo de relaciones para optimizar la vista.
     * Esto permite acceder a $departamento->restaurantes_count 
     * y $departamento->municipios_count directamente sin sobrecargar la base de datos.
     * * @var array<int, string>
     */
    protected $withCount = ['restaurantes', 'municipios'];

    /**
     * Obtener los municipios asociados al departamento.
     * Relación de Uno a Muchos (Un departamento tiene muchos municipios).
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function municipios(): HasMany
    {
        return $this->hasMany(Municipio::class);
    }

    /**
     * Obtener los restaurantes asociados al departamento.
     * Relación de Uno a Muchos (Un departamento tiene muchos restaurantes).
     * * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function restaurantes(): HasMany
    {
        return $this->hasMany(Restaurante::class);
    }
}