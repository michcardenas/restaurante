<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingrediente;

class IngredientesTableSeeder extends Seeder
{
    public function run()
    {
        $ingredientes = [
            'tomato', 'lemon', 'potato', 'rice', 'ketchup', 
            'lettuce', 'onion', 'cheese', 'meat', 'chicken'
        ];

        foreach ($ingredientes as $ingrediente) {
            Ingrediente::create([
                'nombre' => $ingrediente,
                'cantidad_disponible' => 5
            ]);
        }
    }
}
