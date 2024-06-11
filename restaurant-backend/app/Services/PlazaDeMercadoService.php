<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlazaDeMercadoService
{
    public function comprarIngrediente($ingrediente)
    {
        // Convertir el nombre del ingrediente a minúsculas
        $ingrediente = strtolower($ingrediente);

        // Construir la URL con los parámetros en la cadena de consulta
        $url = 'https://recruitment.alegra.com/api/farmers-market/buy';
        $query = http_build_query(['ingredient' => $ingrediente]);
        $fullUrl = $url . '?' . $query;

        // Log para la URL completa
        Log::info('Realizando solicitud a:', [
            'fullUrl' => $fullUrl
        ]);

        $response = Http::get($fullUrl);

        $data = $response->json();

        Log::info('Compra de ingrediente:', [
            'ingrediente' => $ingrediente,
            'response' => $data
        ]);

        if (!isset($data['quantitySold'])) {
            $data['quantitySold'] = 0;
        }

        return $data;
    }
}
