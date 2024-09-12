<?php

namespace App\Http\Controllers;

use App\Services\MunicipioService;

class MunicipioController extends Controller
{
    protected $municipioService;

    public function __construct(MunicipioService $municipioService)
    {
        $this->municipioService = $municipioService;
    }

    // Obtener municipios
    public function index()
    {
        $municipios = $this->municipioService->getMunicipios();
        return response()->json($municipios);
    }

    // Obtener predicción del tiempo de un municipio
    public function show($municipioId)
    {
        $tiempo = $this->municipioService->getTiempo($municipioId);
        return response()->json($tiempo);
    }
}
