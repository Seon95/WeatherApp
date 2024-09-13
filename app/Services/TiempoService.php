<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TiempoService
{
    protected $apiUrl = 'https://opendata.aemet.es/opendata/api/prediccion/especifica/municipio/diaria/';
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function fetchTiempo($municipioId)
    {
        try {
            $url = $this->apiUrl . $municipioId;

            $response = Http::withHeaders([
                'api_key' => $this->apiKey
            ])->get($url);

            if (!$response->successful()) {
                throw new \Exception('Error en la solicitud a la API de AEMET: ' . $response->status());
            }

            $data = $response->json();

            if (!isset($data['datos'])) {
                throw new \Exception('No se pudo obtener la URL de los datos del tiempo');
            }

            $tiempoResponse = Http::get($data['datos']);

            if (!$tiempoResponse->successful()) {
                throw new \Exception('Error al obtener los datos del tiempo: ' . $tiempoResponse->status());
            }

            $tiempoData = $tiempoResponse->json();

            if (!$tiempoData || !is_array($tiempoData) || empty($tiempoData)) {
                throw new \Exception('No se pudo obtener la predicción del tiempo');
            }

            $prediccion = $tiempoData[0]['prediccion']['dia'][0];

            $probPrecipitacionPorPeriodo = $this->getProbabilidadPrecipitacion($prediccion['probPrecipitacion']);

            return [
                'municipio' => $municipioId,
                'fecha' => $prediccion['fecha'],
                'temperatura_min' => $prediccion['temperatura']['minima'],
                'temperatura_max' => $prediccion['temperatura']['maxima'],
                'estado_cielo' => $this->getEstadoCielo($prediccion['estadoCielo']),
                'probabilidad_precipitacion' => $probPrecipitacionPorPeriodo,
            ];
        } catch (\Exception $e) {
            Log::error('Error en TiempoService', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['error' => $e->getMessage()];
        }
    }

    private function getEstadoCielo($estadoCielo)
    {
        foreach ($estadoCielo as $estado) {
            if (isset($estado['periodo']) && $estado['periodo'] === '00-24') {
                return $estado['descripcion'] ?? '';
            }
        }
        return '';
    }

    private function getProbabilidadPrecipitacion($probPrecipitacion)
    {
        $periodos = ['00-06', '06-12', '12-18', '18-24'];
        $resultado = [];

        foreach ($periodos as $periodo) {
            $resultado[$periodo] = $this->findPeriodoValue($probPrecipitacion, $periodo);
        }

        return $resultado;
    }

    private function findPeriodoValue($array, $periodo)
    {
        foreach ($array as $item) {
            if (isset($item['periodo']) && $item['periodo'] === $periodo) {
                return $item['value'] ?? 0;
            }
        }
        return 0;
    }
}
