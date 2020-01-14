<?php

namespace Inmuebles\Providers;

use Illuminate\Support\ServiceProvider;
use Inmuebles\Services\FotosService;

class FotosServiceProvider extends ServiceProvider
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
        $this->app->bind('Inmuebles\Services\FotosService', function ($app) {
          return new FotosService();
        });
    }
}
