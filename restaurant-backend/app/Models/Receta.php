<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $fillable = ['nombre'];

    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'ingrediente_receta')->withPivot('cantidad');
    }
}
