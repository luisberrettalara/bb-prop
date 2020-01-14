<?php

namespace Inmuebles\Providers;

use Illuminate\Support\ServiceProvider;

use Inmuebles\Services\Checkout\CheckoutService;
use Inmuebles\Services\Checkout\MercadoPagoCheckoutService;

class CheckoutServiceProvider extends ServiceProvider {

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
        $this->app->singleton(CheckoutService::class, function ($app) {
            return new MercadoPagoCheckoutService(resolve('MP'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [ CheckoutService::class ];
    }

}