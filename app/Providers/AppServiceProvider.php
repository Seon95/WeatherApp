<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\BaseApiService;
use App\Services\TiempoService;
use App\Services\MunicipioService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(BaseApiService::class, function ($app) {
            return new BaseApiService(env('VITE_AEMET_API_KEY'));
        });

        $this->app->bind(TiempoService::class, function ($app) {
            return new TiempoService($app->make(BaseApiService::class));
        });

        $this->app->bind(MunicipioService::class, function ($app) {
            return new MunicipioService($app->make(BaseApiService::class));
        });
    }

    public function boot()
    {
        //
    }
}
