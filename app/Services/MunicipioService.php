<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Municipio;
use Illuminate\Support\Facades\Cache;

class MunicipioService
{
    protected $apiUrl = 'https://opendata.aemet.es/opendata/api/maestro/municipios';
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getMunicipios()
    {
        $municipiosDB = Municipio::all();

        if ($municipiosDB->isEmpty()) {
            $municipiosAPI = $this->getMunicipiosFromAPI();

            if (!isset($municipiosAPI['error'])) {
                Municipio::insert($municipiosAPI);

                Cache::put('municipios', $municipiosAPI, 60 * 24);

                return $municipiosAPI;
            }

            return $municipiosAPI;
        }

        return Cache::remember('municipios', 60 * 24, function () use ($municipiosDB) {
            return $municipiosDB;
        });
    }

    public function importMunicipiosFromAPI()
    {
        $municipiosAPI = $this->getMunicipiosFromAPI();

        if (!isset($municipiosAPI['error'])) {
            Municipio::truncate();
            Municipio::insert($municipiosAPI);

            Cache::put('municipios', $municipiosAPI, 60 * 24);

            return $municipiosAPI;
        }

        return $municipiosAPI;
    }

    private function getMunicipiosFromAPI()
    {
        try {
            $response = Http::withHeaders([
                'api_key' => $this->apiKey
            ])->get($this->apiUrl);

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

            $responseBody = $municipiosResponse->body();

            $encodings = ['UTF-8', 'ISO-8859-1', 'Windows-1252'];
            $decodedData = null;

            foreach ($encodings as $encoding) {
                $attempt = iconv($encoding, 'UTF-8//IGNORE', $responseBody);
                $decodedData = json_decode($attempt, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    break;
                }
            }

            if ($decodedData === null) {
                throw new \Exception('No se pudo decodificar el JSON después de intentar varias codificaciones');
            }

            if (!is_array($decodedData)) {
                throw new \Exception('Los datos decodificados no son un array');
            }

            $municipios = array_map(function ($municipio) {
                $idSinPrefijo = str_replace('id', '', $municipio['id'] ?? 'ID no disponible');
                return [
                    'id' => $idSinPrefijo,
                    'nombre' => $municipio['nombre'] ?? 'Nombre no disponible'
                ];
            }, $decodedData);

            return $municipios;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
