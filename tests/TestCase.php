<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\ClearLogsServiceProvider;
use Dainsys\Timy\TimyServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * The log directory path.
     *
     * @var string
     */
    protected $logDirectory;

    /**
     * Executed before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadLaravelMigrations();
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
