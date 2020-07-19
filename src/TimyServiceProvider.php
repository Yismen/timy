<?php

namespace Dainsys\Timy;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class TimyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Broadcast::routes();
        require_once(__DIR__ . '/../routes/channels.php');

        $this
            ->registerPublishables()
            ->loadComponents();

        $this->app->bind('TimyUser', config('timy.models.user'));

        SecureGates::boot();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/timy.php',
            'timy'
        );
    }

    private function registerPublishables()
    {
        $this->publishes([
            __DIR__ . '/../config/timy.php' => config_path('timy.php')
        ], 'timy-config');

        $this->publishes([
            __DIR__ . '/../resources/js/components' => resource_path('js/components'),
        ], 'timy-components');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/dainsys/timy')
        ], 'timy-views');

        return $this;
    }

    protected function loadComponents()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'timy');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadFactoriesFrom(__DIR__ . '/../database/factories');
        require_once(__DIR__ . '/../helpers/helpers.php');

        return $this;
    }
}
