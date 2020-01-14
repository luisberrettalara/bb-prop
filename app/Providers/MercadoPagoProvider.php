<?php

namespace Inmuebles\Providers;

use Illuminate\Support\ServiceProvider;
use MP;

class MercadoPagoProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(MP::class, function ($app) {
            return new MP(config('mercadopago.client_id'), config('mercadopago.client_secret'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [ MP::class ];
    }
}
