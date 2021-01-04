<?php

namespace Dainsys\Timy\Tests;

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
    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->artisan('migrate');

        Auth::routes();
    }

    /**
     * Executed after each test.
     */
    public function tearDown(): void
    {
        parent::tearDown();
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
