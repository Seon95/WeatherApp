<?php

namespace App\Http\Controllers;

use App\Services\MunicipioService;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    protected $municipioService;

    public function __construct(MunicipioService $municipioService)
    {
        $this->municipioService = $municipioService;
    }

    public function index()
    {
        $municipios = $this->municipioService->getMunicipios();

        if (isset($municipios['error'])) {
            return response()->json($municipios, 500);
        }

        return response()->json($municipios);
    }
}
