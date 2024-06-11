<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Receta;
use App\Models\Ingrediente;

class RecetasTableSeeder extends Seeder
{
    public function run()
    {
        $recetas = [
            [
                'nombre' => 'Ensalada César',
                'ingredientes' => [
                    ['nombre' => 'lettuce', 'cantidad' => 1],
                    ['nombre' => 'chicken', 'cantidad' => 1],
                    ['nombre' => 'cheese', 'cantidad' => 1],
                    ['nombre' => 'lemon', 'cantidad' => 1],
                ]
            ],
            [
                'nombre' => 'Arroz con Pollo',
                'ingredientes' => [
                    ['nombre' => 'rice', 'cantidad' => 1],
                    ['nombre' => 'chicken', 'cantidad' => 1],
                    ['nombre' => 'onion', 'cantidad' => 1],
                ]
            ],
            [
                'nombre' => 'Hamburguesa',
                'ingredientes' => [
                    ['nombre' => 'meat', 'cantidad' => 1],
                    ['nombre' => 'lettuce', 'cantidad' => 1],
                    ['nombre' => 'tomato', 'cantidad' => 1],
                    ['nombre' => 'cheese', 'cantidad' => 1],
                    ['nombre' => 'ketchup', 'cantidad' => 1],
                ]
            ],
            [
                'nombre' => 'Sopa de Papa',
                'ingredientes' => [
                    ['nombre' => 'potato', 'cantidad' => 2],
                    ['nombre' => 'onion', 'cantidad' => 1],
                    ['nombre' => 'cheese', 'cantidad' => 1],
                ]
            ],
            [
                'nombre' => 'Pollo al Limón',
                'ingredientes' => [
                    ['nombre' => 'chicken', 'cantidad' => 1],
                    ['nombre' => 'lemon', 'cantidad' => 1],
                    ['nombre' => 'rice', 'cantidad' => 1],
                ]
            ],
            [
                'nombre' => 'Ensalada de Tomate y Queso',
                'ingredientes' => [
                    ['nombre' => 'tomato', 'cantidad' => 2],
                    ['nombre' => 'cheese', 'cantidad' => 1],
                    ['nombre' => 'lettuce', 'cantidad' => 1],
                    ['nombre' => 'onion', 'cantidad' => 1],
                ]
            ],
        ];

        foreach ($recetas as $recetaData) {
            $receta = Receta::create(['nombre' => $recetaData['nombre']]);

            foreach ($recetaData['ingredientes'] as $ingredienteData) {
                $ingrediente = Ingrediente::where('nombre', $ingredienteData['nombre'])->first();
                $receta->ingredientes()->attach($ingrediente->id, ['cantidad' => $ingredienteData['cantidad']]);
            }
        }
    }
}
