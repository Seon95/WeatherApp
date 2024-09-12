<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TiempoController extends Controller
{
    protected $apiKey;
    protected $baseUrl = 'https://opendata.aemet.es/opendata/api/prediccion/especifica/municipio/diaria/';

    public function __construct()
    {
        // Asignar la API key manualmente
        $this->apiKey = 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJtYXJvdWFuXzM5QGhvdG1haWwuY29tIiwianRpIjoiNjAxYmM3ZGYtY2I5NS00M2U5LTk2MmYtNGU5NGQxMWY0NTQ2IiwiaXNzIjoiQUVNRVQiLCJpYXQiOjE3MjU4MjEwNDksInVzZXJJZCI6IjYwMWJjN2RmLWNiOTUtNDNlOS05NjJmLTRlOTRkMTFmNDU0NiIsInJvbGUiOiIifQ.41pKSJ6k0kNCZN3JQptRs-GA1U4sQkf0npwyifVfShc
';
    }

    public function getTiempo($municipioId)
    {
        try {
            if (!$this->apiKey) {
                throw new \Exception('API key no configurada');
            }

            $response = Http::withHeaders([
                'api_key' => $this->apiKey
            ])->get($this->baseUrl . $municipioId);

            if (!$response->successful()) {
                throw new \Exception('Error al obtener la URL de los datos: ' . $response->status());
            }

            $data = $response->json();

            if (!isset($data['datos'])) {
                throw new \Exception('La respuesta no contiene la URL de los datos');
            }

            $tiempoResponse = Http::get($data['datos']);

            if (!$tiempoResponse->successful()) {
                throw new \Exception('Error al obtener los datos del tiempo: ' . $tiempoResponse->status());
            }

            $tiempoData = $tiempoResponse->json();

            if (!$tiempoData || !is_array($tiempoData) || count($tiempoData) === 0) {
                throw new \Exception('Datos del tiempo no válidos');
            }

            $prediccion = $tiempoData[0]['prediccion']['dia'][0];

            $probPrecipitacionPorPeriodo = [
                '00-06' => 0,
                '06-12' => 0,
                '12-18' => 0,
                '18-24' => 0
            ];

            foreach ($prediccion['probPrecipitacion'] as $periodo) {
                if (isset($periodo['periodo']) && isset($periodo['value'])) {
                    $probPrecipitacionPorPeriodo[$periodo['periodo']] = $periodo['value'];
                }
            }

            return response()->json([
                'fecha' => $prediccion['fecha'],
                'temperatura_min' => $prediccion['temperatura']['minima'],
                'temperatura_max' => $prediccion['temperatura']['maxima'],
                'estado_cielo' => collect($prediccion['estadoCielo'])
                    ->firstWhere('periodo', '00-24')['descripcion'] ?? '',
                'probabilidad_precipitacion' => $probPrecipitacionPorPeriodo,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
