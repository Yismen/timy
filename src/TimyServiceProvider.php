<?php

namespace Dainsys\Timy;

use Dainsys\Timy\Console\CloseInactiveTimersCommand;
use Dainsys\Timy\Console\PreviousDateHoursReport;
use Dainsys\Timy\Console\TimersRunningForTooLong;
use Dainsys\Timy\Console\UsersWithTooManyHours;
use Dainsys\Timy\Http\Livewire\Dispositions;
use Dainsys\Timy\Http\Livewire\ForcedTimerManagement;
use Dainsys\Timy\Http\Livewire\OpenTimersMonitor;
use Dainsys\Timy\Http\Livewire\RolesManagement;
use Dainsys\Timy\Http\Livewire\TeamCreateComponent;
use Dainsys\Timy\Http\Livewire\TeamEditComponent;
use Dainsys\Timy\Http\Livewire\TeamsTable;
use Dainsys\Timy\Http\Livewire\TimerControl;
use Dainsys\Timy\Http\Livewire\TimersTable;
use Dainsys\Timy\Http\Livewire\UserHoursInfo;
use Dainsys\Timy\Providers\EventServiceProvider;
use Dainsys\Timy\Tests\Mocks\UserMockery;
use Dainsys\Timy\View\Components\InfoBox;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class TimyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this
            ->registerPublishables()
            ->loadServiceComponents()
            ->registerLivewireComponents()
            ->scheduleConsoleCommands();

        SecureGates::boot();
    }

    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        if ($this->app->runningInConsole()) {
            $this->commands(
                CloseInactiveTimersCommand::class,
                TimersRunningForTooLong::class,
                UsersWithTooManyHours::class,
                PreviousDateHoursReport::class,
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
            __DIR__ . '/../resources/views' => resource_path('views/vendor/timy')
        ], 'timy-views');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/timy'),
        ], 'timy-lang');

        return $this;
    }

    protected function loadServiceComponents()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'timy');
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'timy');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->loadFactoriesFrom(__DIR__ . '/../database/factories');

        return $this;
    }

    protected function registerLivewireComponents()
    {
        Livewire::component('timy::timer-control', TimerControl::class);
        Livewire::component('timy::open-timers-monitor', OpenTimersMonitor::class);
        Livewire::component('timy::timers-table', TimersTable::class);
        Livewire::component('timy::role-management', RolesManagement::class);
        Livewire::component('timy::forced-timer-management', ForcedTimerManagement::class);
        Livewire::component('timy::user-hours-info', UserHoursInfo::class);
        Livewire::component('timy::teams-table', TeamsTable::class);
        Livewire::component('timy::dispositions', Dispositions::class);
        Livewire::component('timy::team-create-component', TeamCreateComponent::class);
        Livewire::component('timy::team-edit-component', TeamEditComponent::class);

        Blade::component('timy-info-box', InfoBox::class);

        return $this;
    }

    protected function scheduleConsoleCommands()
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('timy:close-inactive-timers')->everyTenMinutes();
            $schedule->command('timy:timers-running-for-too-long')->everyThirtyMinutes();
            $schedule->command('timy:users-with-too-many-hours')->everyThirtyMinutes();
            $schedule->command('timy:previous-date-hours-report')->dailyAt('07:00');
        });

        return $this;
    }
}
