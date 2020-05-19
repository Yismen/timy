<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\ClearLogsServiceProvider;
use Dainsys\Timy\TimyServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * The log directory path.
     *
     * @var string
     */
    protected $logDirectory;

    public $user;

    /**
     * Executed before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadLaravelMigrations();
        $this->user = factory(\App\User::class)->create();
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
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [TimyServiceProvider::class];
    }
}
