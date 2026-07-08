<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'departamento_id',
        'municipio_id',
        'role',
        'estado',
        'restaurante_id',
        'gastrobar_id',
        'google_id',
        'avatar',
        'telefono',
        'idioma',
        'pregunta_seguridad',
        'respuesta_seguridad',
        'disponible',
        'vehiculo',
        'placa',
        'lat',
        'lng',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'disponible'        => 'boolean',
        ];
    }

    public function departamento()
    {
        return $this->belongsTo(\App\Models\Departamento::class);
    }

    public function municipio()
    {
        return $this->belongsTo(\App\Models\Municipio::class);
    }

    public function restaurante()
    {
        return $this->belongsTo(\App\Models\Restaurante::class);
    }

    public function gastrobar()
    {
        return $this->belongsTo(\App\Models\Gastrobar::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRestaurante(): bool
    {
        return $this->role === 'restaurante';
    }

    public function isGastrobar(): bool
    {
        return $this->role === 'gastrobar';
    }

    public function isUsuario(): bool
    {
        return $this->role === 'usuario';
    }

    public function isMotorizado(): bool
    {
        return $this->role === 'motorizado';
    }

    public function pedidosEntregados()
    {
        return $this->hasMany(\App\Models\PedidoGastrobar::class, 'motorizado_id');
    }

    /**
     * Scope: motorizados disponibles dentro de un radio (en km) desde un punto dado.
     * Uso: User::motorizadosCerca($lat, $lng, 5)->get();
     *
     * Nota: se usa whereRaw en vez de having('distancia_km', ...) porque
     * PostgreSQL no permite referenciar un alias de SELECT dentro de HAVING
     * sin GROUP BY (a diferencia de MySQL, que sí lo permite). Con whereRaw
     * se repite la formula completa, lo cual es compatible con ambos motores.
     *
     * Se filtra por estado = 'activo' para excluir motorizados suspendidos:
     * sin esto, un motorizado suspendido seguía apareciendo como "disponible
     * cerca" para negociar pedidos, ya que solo se validaba `disponible` (el
     * switch de conectado/desconectado) y no el estado de la cuenta.
     */
    public function scopeMotorizadosCerca($query, float $lat, float $lng, float $radioKm = 5)
    {
        $haversine = "(6371 * acos(cos(radians($lat))
            * cos(radians(lat))
            * cos(radians(lng) - radians($lng))
            + sin(radians($lat))
            * sin(radians(lat))))";

        return $query->where('role', 'motorizado')
            ->where('estado', 'activo')
            ->where('disponible', true)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->selectRaw("users.*, {$haversine} AS distancia_km")
            ->whereRaw("{$haversine} <= ?", [$radioKm])
            ->orderBy('distancia_km', 'asc');
    }
}
