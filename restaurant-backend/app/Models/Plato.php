<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    protected $fillable = ['receta_id', 'nombre'];

    public function receta()
    {
        return $this->belongsTo(Receta::class);
    }
}
