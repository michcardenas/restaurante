<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = ['ingrediente_id', 'cantidad_comprada'];

    public function ingrediente()
    {
        return $this->belongsTo(Ingrediente::class);
    }
}
