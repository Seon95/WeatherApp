<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MunicipioService;

class FetchMunicipios extends Command
{
    protected $signature = 'fetch:municipios';
    protected $description = 'Fetch municipios from API and store in database';

    public function handle(MunicipioService $municipioService)
    {
        $municipios = $municipioService->getMunicipios();

        if (isset($municipios['error'])) {
            $this->error($municipios['error']);
        } else {
            $this->info('Municipios fetched and stored successfully.');
            $this->info('Total municipios: ' . count($municipios));
        }
    }
}