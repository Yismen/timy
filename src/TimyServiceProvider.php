<?php

namespace Dainsys\Timy;

use Dainsys\Timy\Http\Livewire\InfoBox;
use Dainsys\Timy\Http\Livewire\OpenTimersMonitor;
use Dainsys\Timy\Http\Livewire\RolesManagement;
use Dainsys\Timy\Http\Livewire\TimerControl;
use Dainsys\Timy\Http\Livewire\TimersTable;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class TimyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this
            ->registerPublishables()
            ->loadComponents()
            ->registerLivewireComponents();

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

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/timy'),
        ]);

        return $this;
    }

    protected function loadComponents()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'timy');
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'timy');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadFactoriesFrom(__DIR__ . '/../database/factories');
        require(__DIR__ . '/../helpers/helpers.php');

        return $this;
    }

    protected function registerLivewireComponents()
    {
        Livewire::component('timy::timer-control', TimerControl::class);
        Livewire::component('timy::open-timers-monitor', OpenTimersMonitor::class);
        Livewire::component('timy::info-box', InfoBox::class);
        Livewire::component('timy::timers-table', TimersTable::class);
        Livewire::component('timy::role-management', RolesManagement::class);

        return $this;
    }
}
