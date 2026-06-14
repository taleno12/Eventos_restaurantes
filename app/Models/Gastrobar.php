<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Gastrobar extends Model
{
    use HasFactory;

    protected $table = 'gastrobares';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',        // ← agregado
        'tipo_cocina',
        'tipo_bar',
        'descripcion',
        'hora_apertura',
        'hora_cierre',
        'dias_atencion',
        'tipo_musica',
        'capacidad',
        'ambiente',
        'departamento_id',
        'municipio_id',
        'direccion',
        'latitud',
        'longitud',
        'telefono',
        'whatsapp',
        'instagram',
        'facebook',
        'tiktok',
        'imagen_principal',
        'galeria',
        'activo',
    ];

    protected $casts = [
        'dias_atencion' => 'array',
        'galeria'       => 'array',
        'activo'        => 'boolean',
        'latitud'       => 'float',
        'longitud'      => 'float',
        'hora_apertura' => 'string',
        'hora_cierre'   => 'string',
    ];

    // ── RELACIONES ──────────────────────────────────────────────

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(GastrobarFoto::class);
    }

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }

    public function empleos(): HasMany
    {
        return $this->hasMany(Empleo::class);
    }

    /**
     * Un gastrobar TIENE UN propietario (usuario con rol 'gastrobar')
     */
    public function propietario(): HasOne
    {
        return $this->hasOne(User::class, 'gastrobar_id')->where('role', 'gastrobar');
    }

    // ── ACCESSORS ───────────────────────────────────────────────

    public function getImagenUrlAttribute(): string
    {
        if ($this->imagen_principal && Storage::disk('public')->exists($this->imagen_principal)) {
            return Storage::url($this->imagen_principal);
        }
        return 'https://placehold.co/800x500/1a1a2e/9333ea?text=Gastrobar';
    }

    public function getDiasTextoAttribute(): string
    {
        if (empty($this->dias_atencion)) return 'No especificado';
        return implode(', ', array_map('ucfirst', $this->dias_atencion));
    }

    public function getHorarioTextoAttribute(): string
    {
        if (!$this->hora_apertura && !$this->hora_cierre) return 'No especificado';
        return ($this->hora_apertura ?? '--') . ' - ' . ($this->hora_cierre ?? '--');
    }
}
