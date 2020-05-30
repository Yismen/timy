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
            __DIR__ . '/../config/timy.php' => config_path('timy.php')
        ], 'timy-config');

        $this->publishes([
            __DIR__ . '/../components' => base_path('resources/js/components'),
        ], 'timy-components');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/dainsys/timy')
        ], 'timy-views');

        $this->publishes([
            __DIR__ . '/../public/vendor/timy' => public_path('vendor/dainsys/timy'),
        ], 'timy-public');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'timy');
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadFactoriesFrom(__DIR__ . '/../database/factories');

        $this->app->bind('TimyUser', config('timy.models.user'));

        SecureGates::boot();

        require_once(__DIR__ . '/../helpers/helpers.php');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/timy.php',
            'timy'
        );
    }
}
