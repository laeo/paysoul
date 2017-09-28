<?php

namespace Paysoul;

use Illuminate\Support\ServiceProvider;

class PaysoulServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (false === is_file(config_path('paysoul.php'))) {
            $this->publishes([
                dirname(__DIR__) . '/config/paysoul.php' => config_path('paysoul.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/paysoul.php', 'paysoul');

        $this->app->singleton(Paysoul::class, function ($app) {
            return new Paysoul(config('paysoul'));
        });

        $this->app->alias(Paysoul::class, 'paysoul');
    }

    public function provides()
    {
        return [Paysoul::class, 'paysoul'];
    }
}
