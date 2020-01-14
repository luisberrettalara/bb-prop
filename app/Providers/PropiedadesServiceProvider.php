<?php

namespace Inmuebles\Providers;

use Illuminate\Support\ServiceProvider;
use Inmuebles\Services\PropiedadesService;

class PropiedadesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Inmuebles\Services\PropiedadesService', function ($app) {
          return new PropiedadesService();
        });
    }
}
