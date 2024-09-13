<?php

namespace App\Services;

use App\Models\Municipio;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MunicipioService
{
    public function getMunicipios()
    {
        return Cache::remember('municipios', 60 * 24, function () {
            try {
                $municipios = Municipio::select('id', 'nombre')->get();

                if ($municipios->isEmpty()) {
                    Log::warning('No se encontraron municipios en la base de datos');
                }

                return $municipios->map(function ($municipio) {
                    return [
                        'id' => $municipio->id,
                        'nombre' => $municipio->nombre
                    ];
                })->all();
            } catch (\Exception $e) {
                Log::error('Error al obtener municipios de la base de datos', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return ['error' => 'Error interno del servidor'];
            }
        });
    }
}
