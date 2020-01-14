<?php

namespace Inmuebles\Providers;

use Illuminate\Support\ServiceProvider;
use Inmuebles\Services\Afip\FacturacionService;

class FacturacionServiceProvider extends ServiceProvider
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
        $this->app->bind('Inmuebles\Services\Afip\FacturacionService', function ($app) {
          return new FacturacionService;
        });
    }
}
