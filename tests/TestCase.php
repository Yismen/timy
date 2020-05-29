<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\TimyServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Support\Facades\Route;

class TestCase extends OrchestraTestCase
{
    /**
     * The log directory path.
     *
     * @var string
     */

    public $user;

    /**
     * Executed before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->withFactories(database_path('/factories'));
        $this->loadLaravelMigrations();
        $this->artisan('migrate');

        $this->user = factory(config('timy.models.user'))->create();

        Route::get('/login')->name('login');
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
        return [TimyServiceProvider::class];
    }
}
