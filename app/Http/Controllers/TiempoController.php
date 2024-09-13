<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TiempoService
{
    protected $apiKey;
    protected $apiUrl = 'https://opendata.aemet.es/opendata/api/prediccion/especifica/municipio/diaria/';

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function fetchTiempo($municipioId)
    {
        try {
            $url = $this->apiUrl . $municipioId . '?api_key=' . $this->apiKey;

            $response = Http::get($url);

            if (!$response->successful()) {
                throw new \Exception('Error en la solicitud a la API de AEMET: ' . $response->status());
            }

            // Resto del código para procesar la respuesta...

        } catch (\Exception $e) {
            Log::error('Error en TiempoService', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['error' => $e->getMessage()];
        }
    }
}
