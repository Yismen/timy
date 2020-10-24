<?php

namespace Dainsys\Timy;

use Dainsys\Timy\Console\Commands\CloseInactiveTimersCommand;
use Dainsys\Timy\Http\Livewire\ForcedTimerManagement;
use Dainsys\Timy\Http\Livewire\InfoBox;
use Dainsys\Timy\Http\Livewire\OpenTimersMonitor;
use Dainsys\Timy\Http\Livewire\RolesManagement;
use Dainsys\Timy\Http\Livewire\TimerControl;
use Dainsys\Timy\Http\Livewire\TimersTable;
use Dainsys\Timy\Providers\EventServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
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

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('timy:close-inactive-timers')->everyMinute();
        });
    }

    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        if ($this->app->runningInConsole()) {
            $this->commands(
                CloseInactiveTimersCommand::class
            );
        }

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
        Livewire::component('timy::forced-timer-management', ForcedTimerManagement::class);

        return $this;
    }
}
