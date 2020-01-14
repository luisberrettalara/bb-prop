<?php

namespace Inmuebles\Providers;

use Illuminate\Support\ServiceProvider;
use Inmuebles\Services\Mercadopago\IpnService;

class IpnServiceProvider extends ServiceProvider {

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
        $this->app->singleton(IpnService::class, function ($app) {
            return new IpnService(resolve('MP'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [ IpnService::class ];
    }
}
