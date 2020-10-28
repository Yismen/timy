<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Components\ComponentsServiceProvider;
use Dainsys\Timy\TimyServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\UiServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{
    /**
     * Executed before each test.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/../database/factories');
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
            LivewireServiceProvider::class,
            UiServiceProvider::class,
            ComponentsServiceProvider::class,
            TimyServiceProvider::class,
        ];
    }

    protected function user($attributes = [])
    {
        return factory(resolve('TimyUserClass'))->create($attributes);
    }
}
