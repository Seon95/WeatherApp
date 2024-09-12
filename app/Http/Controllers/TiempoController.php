<?php

namespace App\Http\Controllers;

use App\Services\TiempoService;
use Illuminate\Http\Request;

class TiempoController extends Controller
{
    protected $tiempoService;

    public function __construct(TiempoService $tiempoService)
    {
        $this->tiempoService = $tiempoService;
    }

    public function show($municipioId)
    {
        $tiempo = $this->tiempoService->fetchTiempo($municipioId);

        if (isset($tiempo['error'])) {
            return response()->json($tiempo, 500);
        }

        return response()->json($tiempo);
    }
}
