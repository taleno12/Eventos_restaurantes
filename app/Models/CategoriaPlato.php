<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaPlato extends Model
{
    protected $table = 'categorias_plato';

    protected $fillable = ['restaurante_id', 'nombre', 'orden'];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function platos()
    {
        return $this->hasMany(Plato::class, 'categoria_id');
    }

    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }
}
