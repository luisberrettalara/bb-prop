<?php

namespace Inmuebles\Providers;

use Illuminate\Support\ServiceProvider;
use Inmuebles\Services\Paquetes\PaquetesService;

class PaquetesServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

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
        $this->app->singleton(PaquetesService::class, function ($app) {
            return new PaquetesService;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ PaquetesService::class ];
    }
}
