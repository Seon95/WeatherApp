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
            $url = $this->apiUrl . $municipioId;

            // Pasar la clave API en el header en lugar de en la URL
            $response = Http::withHeaders([
                'api_key' => $this->apiKey
            ])->get($url);

            if (!$response->successful()) {
                throw new \Exception('Error en la solicitud a la API de AEMET: ' . $response->status());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error en TiempoService', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['error' => $e->getMessage()];
        }
    }
}
