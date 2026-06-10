<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $restaurante_id
 * @property int|null $gastrobar_id
 * @property int $rating
 * @property string|null $title
 * @property string|null $body
 * @property string|null $comentario
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Restaurante|null $restaurante
 * @property-read \App\Models\Gastrobar|null $gastrobar
 * @property-read \App\Models\User $user
 * @mixin \Eloquent
 */
class Review extends Model
{
    protected $fillable = [
        'restaurante_id',
        'gastrobar_id',
        'user_id',
        'rating',
        'title',
        'body',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Relaciones
    public function restaurante(): BelongsTo
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function gastrobar(): BelongsTo
    {
        return $this->belongsTo(Gastrobar::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
