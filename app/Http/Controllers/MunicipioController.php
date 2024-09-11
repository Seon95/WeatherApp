<?php

namespace App\Http\Controllers;

use App\Services\MunicipioService;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    public function index()
    {
        $municipioService = new MunicipioService();
        $municipios = $municipioService->getMunicipios();

        // Comprobamos si hay un error
        if (isset($municipios['error'])) {
            // Devolver el error como respuesta JSON
            return response()->json(['error' => $municipios['error']], 500);
        } else {
            // Devolver los municipios como JSON
            return response()->json($municipios);
        }
    }
}
