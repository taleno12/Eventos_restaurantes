<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $restaurante_id
 * @property int $rating
 * @property string|null $title
 * @property string|null $body
 * @property string|null $comentario
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Restaurante $restaurante
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereComentario($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereRestauranteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Review whereUserId($value)
 * @mixin \Eloquent
 */
class Review extends Model
{
    protected $fillable = [
        'restaurante_id',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}