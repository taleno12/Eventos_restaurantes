<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastrobarFoto extends Model
{
    protected $fillable = ['gastrobar_id', 'ruta_foto'];

    public function gastrobar()
    {
        return $this->belongsTo(Gastrobar::class);
    }
}
