<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $nombre
 * @property string|null $email
 * @property string|null $tipo_cocina
 * @property string|null $tipo_bar
 * @property string|null $descripcion
 * @property string|null $hora_apertura
 * @property string|null $hora_cierre
 * @property array<array-key, mixed>|null $dias_atencion
 * @property string|null $tipo_musica
 * @property int|null $capacidad
 * @property string|null $ambiente
 * @property int|null $departamento_id
 * @property int|null $municipio_id
 * @property string|null $direccion
 * @property float|null $latitud
 * @property float|null $longitud
 * @property string|null $whatsapp
 * @property string|null $instagram
 * @property string|null $facebook
 * @property string|null $tiktok
 * @property string|null $imagen_principal
 * @property array<array-key, mixed>|null $galeria
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Departamento|null $departamento
 * @property-read string $dias_texto
 * @property-read string $horario_texto
 * @property-read string $imagen_url
 * @property-read \App\Models\Municipio|null $municipio
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereAmbiente($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereCapacidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereDepartamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereDiasAtencion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereGaleria($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereHoraApertura($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereHoraCierre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereImagenPrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereLatitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereLongitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereMunicipioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereTiktok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereTipoBar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereTipoCocina($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereTipoMusica($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Gastrobar whereWhatsapp($value)
 * @mixin \Eloquent
 */
class Gastrobar extends Model
{
    use HasFactory;

    protected $table = 'gastrobares';

    protected $fillable = [
        // Datos generales
        'nombre',
        'email',
        'tipo_cocina',
        'tipo_bar',
        'descripcion',

        // Horarios
        'hora_apertura',
        'hora_cierre',
        'dias_atencion',

        // Ambiente
        'tipo_musica',
        'capacidad',
        'ambiente',

        // Ubicación
        'departamento_id',
        'municipio_id',
        'direccion',
        'latitud',
        'longitud',

        // Redes sociales
        'whatsapp',
        'instagram',
        'facebook',
        'tiktok',

        // Multimedia
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

    // ── ACCESSORS ───────────────────────────────────────────────

    // Devuelve la URL de la imagen principal o un placeholder
    public function getImagenUrlAttribute(): string
    {
        if ($this->imagen_principal && Storage::disk('public')->exists($this->imagen_principal)) {
            return Storage::url($this->imagen_principal);
        }
        return 'https://placehold.co/800x500/1a1a2e/9333ea?text=Gastrobar';
    }

    // Devuelve los días de atención como string legible
    public function getDiasTextoAttribute(): string
    {
        if (empty($this->dias_atencion)) return 'No especificado';
        return implode(', ', array_map('ucfirst', $this->dias_atencion));
    }

    // Devuelve el horario formateado
    public function getHorarioTextoAttribute(): string
    {
        if (!$this->hora_apertura && !$this->hora_cierre) return 'No especificado';
        return ($this->hora_apertura ?? '--') . ' - ' . ($this->hora_cierre ?? '--');
    }
}