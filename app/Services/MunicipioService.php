<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MunicipioService extends BaseApiService
{
    protected $apiUrl = 'https://opendata.aemet.es/opendata/api/maestro/municipios';

    public function getMunicipios()
    {
        return Cache::remember('municipios', 60 * 24, function () {
            try {
                $response = $this->get($this->apiUrl);

                if (!isset($response['datos'])) {
                    throw new \Exception('No se pudo obtener la URL de los datos de municipios');
                }

                $municipiosResponse = Http::get($response['datos']);

                if (!$municipiosResponse->successful()) {
                    throw new \Exception('Error al obtener los datos de municipios: ' . $municipiosResponse->status());
                }

                $municipios = $municipiosResponse->json();

                return array_map(function ($municipio) {
                    return [
                        'id' => $municipio['id'],
                        'nombre' => $municipio['nombre']
                    ];
                }, $municipios);
            } catch (\Exception $e) {
                Log::error('Error en MunicipioService', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return ['error' => $e->getMessage()];
            }
        });
    }
}
