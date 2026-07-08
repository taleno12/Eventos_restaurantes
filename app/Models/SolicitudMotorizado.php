<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudMotorizado extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_motorizado';

    protected $fillable = [
        'user_id',
        'nombre_completo',
        'cedula',
        'fecha_nacimiento',
        'genero',
        'contacto',
        'edad',
        'tipo_vehiculo',
        'placa',
        'foto_perfil',
        'foto_licencia',
        'foto_record_policial',
        'departamento_id',
        'municipio_id',
        'localidad',
        'estado',
        'motivo_rechazo',
        'revisado_por',
        'revisado_at',
    ];

    protected $casts = [
        'edad'             => 'integer',
        'fecha_nacimiento' => 'date',
        'revisado_at'      => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function revisadoPor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }
}
