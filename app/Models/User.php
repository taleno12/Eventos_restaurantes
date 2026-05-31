<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'departamento_id',
        'role',
        'restaurante_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function departamento()
{
    return $this->belongsTo(\App\Models\Departamento::class);
}

//nuevo

public function restaurante()
{
    return $this->belongsTo(\App\Models\Restaurante::class);
}

public function isAdmin(): bool
{
    return $this->role === 'admin';
}

public function isRestaurante(): bool
{
    return $this->role === 'restaurante';
}

public function isUsuario(): bool
{
    return $this->role === 'usuario';
}
}
