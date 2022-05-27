<?php

namespace Kirko\Larawave;

use Illuminate\Support\ServiceProvider;

class LarawaveServiceProvider extends ServiceProvider
{

    protected $defer = false;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('larawave', function ($app) {
            return new Larawave($app->make("request"));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $config = realpath(__DIR__.'/../resources/config/flutterwave.php');

        $this->publishes([
            $config => config_path('flutterwave.php')
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['larawave'];
    }
}
