<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receta;
use App\Models\Ingrediente;
use App\Models\Compra;
use App\Models\Plato;
use App\Services\PlazaDeMercadoService;
use Illuminate\Support\Facades\Log;

class PlatoController extends Controller
{
    protected $plazaDeMercadoService;

    public function __construct(PlazaDeMercadoService $plazaDeMercadoService)
    {
        $this->plazaDeMercadoService = $plazaDeMercadoService;
    }

    public function crearReceta(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string',
            'ingredientes' => 'required|array',
            'ingredientes.*.id' => 'required|exists:ingredientes,id',
            'ingredientes.*.cantidad' => 'required|integer|min:1'
        ]);

        $receta = Receta::create(['nombre' => $request->nombre]);

        foreach ($request->ingredientes as $ingrediente) {
            $receta->ingredientes()->attach($ingrediente['id'], ['cantidad' => $ingrediente['cantidad']]);
        }

        return response()->json(['mensaje' => 'Receta creada con éxito', 'receta' => $receta], 201);
    }

    public function recetaAleatoria()
    {
        $receta = Receta::inRandomOrder()->first();

        if (!$receta) {
            return response()->json(['mensaje' => 'No hay recetas disponibles'], 404);
        }

        return response()->json($receta->load('ingredientes'), 200);
    }

    public function pedirIngredientes(Request $request)
    {
        $this->validate($request, [
            'receta_id' => 'required|exists:recetas,id'
        ]);
    
        $receta = Receta::with('ingredientes')->find($request->receta_id);
    
        $ingredientesNecesarios = [];
        $comprasRealizadas = [];
    
        foreach ($receta->ingredientes as $ingrediente) {
            $cantidadNecesaria = $ingrediente->pivot->cantidad;
            $cantidadDisponible = $ingrediente->cantidad_disponible;
    
            if ($cantidadDisponible < $cantidadNecesaria) {
                $cantidadFaltante = $cantidadNecesaria - $cantidadDisponible;
                $compraResult = $this->plazaDeMercadoService->comprarIngrediente($ingrediente->nombre);
    
                // Añadir registro de logs para la compra
                Log::info('Resultado de la compra:', [
                    'ingrediente' => $ingrediente->nombre,
                    'cantidad_faltante' => $cantidadFaltante,
                    'compraResult' => $compraResult
                ]);
    
                if (isset($compraResult['quantitySold']) && $compraResult['quantitySold'] > 0) {
                    $ingrediente->cantidad_disponible += $compraResult['quantitySold'];
                    $ingrediente->save();
    
                    // Registrar la compra
                    Compra::create([
                        'ingrediente_id' => $ingrediente->id,
                        'cantidad_comprada' => $compraResult['quantitySold']
                    ]);
    
                    $comprasRealizadas[] = [
                        'ingrediente' => $ingrediente->nombre,
                        'cantidad_comprada' => $compraResult['quantitySold']
                    ];
                }
    
                // Verificar nuevamente después de la compra
                if ($ingrediente->cantidad_disponible < $cantidadNecesaria) {
                    $cantidadFaltante = $cantidadNecesaria - $ingrediente->cantidad_disponible;
                    $ingredientesNecesarios[] = [
                        'ingrediente' => $ingrediente->nombre,
                        'cantidad_faltante' => $cantidadFaltante
                    ];
                }
            }
    
            // Descontar la cantidad necesaria solo si es suficiente
            if ($ingrediente->cantidad_disponible >= $cantidadNecesaria) {
                $ingrediente->cantidad_disponible -= $cantidadNecesaria;
                $ingrediente->save();
            }
        }
    
        // Registrar el plato preparado
        Plato::create([
            'receta_id' => $receta->id,
            'nombre' => $receta->nombre
        ]);
    
        if (!empty($ingredientesNecesarios)) {
            return response()->json([
                'mensaje' => 'Ingredientes insuficientes incluso después de comprar',
                'ingredientes_faltantes' => $ingredientesNecesarios,
                'compras_realizadas' => $comprasRealizadas
            ], 400);
        }
    
        return response()->json([
            'mensaje' => 'Ingredientes solicitados y descontados de la bodega',
            'compras_realizadas' => $comprasRealizadas
        ], 200);
    }
    

    public function listarPlatos()
    {
        $platos = Plato::all();
        return response()->json($platos, 200);
    }
    public function showAvailableIngredients()
    {
        try {
            // Obtener todos los ingredientes disponibles en la despensa
            $ingredientes = Ingrediente::where('cantidad_disponible', '>', 0)->get();
            return response()->json($ingredientes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los ingredientes: ' . $e->getMessage()], 500);
        }
    }
}
