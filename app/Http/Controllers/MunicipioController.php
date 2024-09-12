<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\JsonResponse;

class MunicipioController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $municipios = Municipio::select('id', 'nombre')
                ->orderBy('nombre')
                ->get();

            return response()->json($municipios);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los municipios: ' . $e->getMessage()], 500);
        }
    }
}
