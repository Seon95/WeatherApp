<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Municipio;
use Illuminate\Support\Facades\Cache;

class MunicipioService
{
    protected $apiKey;
    protected $municipiosApiUrl = 'https://opendata.aemet.es/opendata/api/maestro/municipios';
    protected $tiempoApiUrl = 'https://opendata.aemet.es/opendata/api/prediccion/especifica/municipio/diaria/';

    public function __construct()
    {
        $this->apiKey = env('AEMET_API_KEY');  // Usamos la variable del entorno
    }

    public function getMunicipios()
    {
        return Cache::remember('municipios', 60 * 24, function () {
            $municipiosDB = Municipio::all();

            if ($municipiosDB->isEmpty()) {
                $municipiosAPI = $this->getMunicipiosFromAPI();

                if (!isset($municipiosAPI['error'])) {
                    Municipio::insert($municipiosAPI);
                    return $municipiosAPI;
                }

                return $municipiosAPI;
            }

            return $municipiosDB;
        });
    }

    private function getMunicipiosFromAPI()
    {
        try {
            $response = Http::withHeaders([
                'api_key' => $this->apiKey
            ])->get($this->municipiosApiUrl);

            if (!$response->successful()) {
                throw new \Exception('Error al obtener la URL de los datos: ' . $response->status());
            }

            $data = $response->json();

            if (!isset($data['datos'])) {
                throw new \Exception('La respuesta no contiene la URL de los datos');
            }

            $municipiosUrl = $data['datos'];

            $municipiosResponse = Http::get($municipiosUrl);

            if (!$municipiosResponse->successful()) {
                throw new \Exception('Error al obtener los datos de municipios: ' . $municipiosResponse->status());
            }

            $decodedData = json_decode($municipiosResponse->body(), true);

            return array_map(function ($municipio) {
                $idSinPrefijo = str_replace('id', '', $municipio['id'] ?? 'ID no disponible');
                return [
                    'id' => $idSinPrefijo,
                    'nombre' => $municipio['nombre'] ?? 'Nombre no disponible'
                ];
            }, $decodedData);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getTiempo($municipioId)
    {
        try {
            $response = Http::get("{$this->tiempoApiUrl}{$municipioId}", [
                'api_key' => $this->apiKey,
            ]);

            $data = $response->json();

            if (isset($data['datos'])) {
                $tiempoResponse = Http::get($data['datos']);
                $tiempoData = $tiempoResponse->json();

                if ($tiempoData && count($tiempoData) > 0) {
                    $prediccion = $tiempoData[0]['prediccion']['dia'][0];

                    // Extraer la probabilidad de precipitación para cada período
                    $probPrecipitacionPorPeriodo = [
                        "00-06" => $this->getPeriodo($prediccion, "00-06"),
                        "06-12" => $this->getPeriodo($prediccion, "06-12"),
                        "12-18" => $this->getPeriodo($prediccion, "12-18"),
                        "18-24" => $this->getPeriodo($prediccion, "18-24"),
                    ];

                    return [
                        'fecha' => $prediccion['fecha'],
                        'temperatura_min' => $prediccion['temperatura']['minima'],
                        'temperatura_max' => $prediccion['temperatura']['maxima'],
                        'estado_cielo' => $this->getEstadoCielo($prediccion),
                        'probabilidad_precipitacion' => $probPrecipitacionPorPeriodo,
                    ];
                }
            }

            throw new \Exception('No se pudo obtener la predicción del tiempo.');
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function getPeriodo($prediccion, $periodo)
    {
        return $prediccion['probPrecipitacion'][0]['value'] ?? 0;
    }

    private function getEstadoCielo($prediccion)
    {
        return $prediccion['estadoCielo'][0]['descripcion'] ?? '';
    }
}
