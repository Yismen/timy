<?php

namespace Dainsys\Timy\Tests;

use App\User;
use ConsoleTVs\Charts\ChartsServiceProvider;
use Dainsys\Components\ComponentsServiceProvider;
use Dainsys\Timy\Models\Role;
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
            UiServiceProvider::class,
            LivewireServiceProvider::class,
            ComponentsServiceProvider::class,
            ChartsServiceProvider::class,
            TimyServiceProvider::class,
        ];
    }

    protected function user($attributes = [], $amount = null)
    {
        return factory(User::class, $amount)->create($attributes);
    }

    protected function timyUser()
    {
        $user =  $this->user();
        $role = Role::where('name', config('timy.roles.user'))->first(); //created at the migration
        $user->assignTimyRole($role);

        return $user;
    }

    protected function superAdminUser()
    {
        return $this->user(['email' => config('timy.super_admin_email')]);
    }

    protected function adminUser()
    {
        $user =  $this->user();
        $role = Role::where('name', config('timy.roles.admin'))->first(); //created at the migration
        $user->assignTimyRole($role);
        return $user;
    }
}
