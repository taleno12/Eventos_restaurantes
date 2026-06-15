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
}
