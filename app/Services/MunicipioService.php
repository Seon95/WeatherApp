<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Municipio;
use Illuminate\Support\Facades\Cache;

class MunicipioService
{
    protected $apiKey = 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJtYXJvdWFuXzM5QGhvdG1haWwuY29tIiwianRpIjoiNjAxYmM3ZGYtY2I5NS00M2U5LTk2MmYtNGU5NGQxMWY0NTQ2IiwiaXNzIjoiQUVNRVQiLCJpYXQiOjE3MjU4MjEwNDksInVzZXJJZCI6IjYwMWJjN2RmLWNiOTUtNDNlOS05NjJmLTRlOTRkMTFmNDU0NiIsInJvbGUiOiIifQ.41pKSJ6k0kNCZN3JQptRs-GA1U4sQkf0npwyifVfShc';
    protected $apiUrl = 'https://opendata.aemet.es/opendata/api/maestro/municipios';

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
            // Paso 1: Obtener la URL de los datos
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

            // Paso 2: Obtener los datos de los municipios
            $municipiosResponse = Http::get($municipiosUrl);

            if (!$municipiosResponse->successful()) {
                throw new \Exception('Error al obtener los datos de municipios: ' . $municipiosResponse->status());
            }

            $responseBody = $municipiosResponse->body();

            // Intentar corregir la codificación
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

            // Verificar que $decodedData es un array
            if (!is_array($decodedData)) {
                throw new \Exception('Los datos decodificados no son un array');
            }

            // Extraer los nombres y los IDs de los municipios sin el prefijo "id"
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
