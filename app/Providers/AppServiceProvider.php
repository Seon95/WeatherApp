<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TiempoService;
use App\Services\MunicipioService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TiempoService::class, function ($app) {
            return new TiempoService(env('VITE_AEMET_API_KEY'));
        });

        $this->app->singleton(MunicipioService::class, function ($app) {
            return new MunicipioService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
