<?php

namespace Dainsys\Timy\Tests;

use Carbon\Carbon;
use ConsoleTVs\Charts\ChartsServiceProvider;
use Dainsys\Components\ComponentsServiceProvider;
use Dainsys\Timy\TimyServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\UiServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{
    use AppTestTrait;
    /**
     * Executed before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        date_default_timezone_set('America/Santo_Domingo');
        Carbon::setTestNow(Carbon::parse('17:00'));
        $this->loadLaravelMigrations();
        $this->artisan('migrate');

        Auth::routes();
    }

    /**
     * Executed after each test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow();
    }

    /**
     * Load the command service provider.
     *
     * @param \Illuminate\Foundationlication $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            UiServiceProvider::class,
            LivewireServiceProvider::class,
            ComponentsServiceProvider::class,
            ChartsServiceProvider::class,
            TimyServiceProvider::class,
        ];
    }
}
