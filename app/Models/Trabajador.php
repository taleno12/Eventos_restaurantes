<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Trabajador extends Model
{
    use HasFactory;

    protected $table = 'trabajadores';

    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'email',
        'telefono',
        'salario',
        'cargo',
        'estado',
        'fecha_ingreso',
        'foto',
        'observaciones',
    ];

    protected $casts = [
        'salario'       => 'decimal:2',
        'fecha_ingreso' => 'date',
    ];

    // ── RELACIONES ──────────────────────────────────────────────

    /**
     * Un trabajador puede pertenecer a MUCHOS departamentos (Many to Many)
     */
    public function departamentos(): BelongsToMany
    {
        return $this->belongsToMany(Departamento::class, 'departamento_trabajador')
                    ->withTimestamps();
    }

    /**
     * Restaurantes disponibles en los departamentos asignados al trabajador
     */
    public function restaurantesDisponibles()
    {
        $departamentoIds = $this->departamentos->pluck('id');

        return Restaurante::whereIn('departamento_id', $departamentoIds)
                          ->orderBy('nombre')
                          ->get();
    }

    /**
     * Gastrobares disponibles en los departamentos asignados al trabajador
     */
    public function gastrobaresDisponibles()
    {
        $departamentoIds = $this->departamentos->pluck('id');

        return Gastrobar::whereIn('departamento_id', $departamentoIds)
                        ->orderBy('nombre')
                        ->get();
    }

    // ── ACCESSORS ───────────────────────────────────────────────

    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }
}
