<?php

namespace Dainsys\Timy;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Http;

class TimyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/timy.php', 'timy');

        $this->publishes([
            __DIR__ . '/config/timy.php' => config_path('timy.php')
        ], 'timy-config');

        $this->publishes([
            __DIR__ . '/components' => base_path('resources/js/components'),
        ], 'timy-components');

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadFactoriesFrom(__DIR__ . '/database/factories');

        $this->app->bind('TimyUser', config('timy.models.user'));

        Gate::define(config('timy.super_admin.role'), function ($user) {
            return $user->email == config('timy.super_admin.email');
        });

        //

    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/timy.php',
            'timy'
        );
    }
}
