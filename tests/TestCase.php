<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\TimyServiceProvider;
use Illuminate\Support\Facades\Auth;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Support\Facades\Route;
use Laravel\Ui\UiServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Executed before each test.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(database_path('/factories'));
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
            TimyServiceProvider::class
        ];
    }

    protected function user($attributes = [])
    {
        return factory(config('timy.models.user'))->create($attributes);
    }
}
