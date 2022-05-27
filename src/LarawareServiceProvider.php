<?php

namespace Kirko\Larawave;

use Illuminate\Support\ServiceProvider;

class LarawareServiceProvider extends ServiceProvider
{

    protected $defer = false;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('laraware', function ($app) {
            return new Laraware($app->make("request"));
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
        return ['laraware'];
    }
}
