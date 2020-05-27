<?php

namespace Dainsys\Timy;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Http;

class TimyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/timy.php' => config_path('timy.php'),
            'timy-config'
        ]);
        $this->publishes([
            __DIR__ . '/components' => base_path('resources/js/components'),
        ], 'timy-components');

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadFactoriesFrom(__DIR__ . '/database/factories');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/timy.php',
            'timy'
        );
    }
}
