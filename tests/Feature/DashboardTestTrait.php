<?php

namespace Dainsys\Timy\Tests\Feature;

use Dainsys\Timy\Charts\UserDailyHoursChart;
use Dainsys\Timy\Models\Disposition;
use Dainsys\Timy\Models\Role;
use Dainsys\Timy\Repositories\UserHoursDaily;
use Dainsys\Timy\Resources\UserTimerResource;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait DashboardTestTrait
{
    /** @test */
    public function guest_are_unauthorized_to_see_users_dashboard()
    {
        $this->get(route('user_dashboard'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_are_unauthorized_to_see_admin_dashboard()
    {
        $this->get(route('admin_dashboard'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function guest_are_unauthorized_to_see_super_admin_dashboard()
    {
        $this->get(route('super_admin_dashboard'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function unauthorized_users_should_not_see_super_admin_dashboard()
    {
        $this->actingAs($this->user())
            ->get(route('super_admin_dashboard'))
            ->assertStatus(403);
    }

    /** @test */
    public function unauthorized_users_should_not_see_admin_dashboard()
    {
        $this->actingAs($this->user())
            ->get(route('admin_dashboard'))
            ->assertStatus(403);
    }

    /** @test */
    public function unauthorized_users_should_not_see_user_dashboard()
    {
        $this->actingAs($this->user())
            ->get(route('user_dashboard'))
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_see_super_admin_dashboard()
    {
        $user =  $this->superAdminUser();

        $this->actingAs($user)
            ->get(route('super_admin_dashboard'))
            ->assertOk()
            ->assertViewIs('timy::dashboards.super-admin')
            ->assertViewHas('dispositions',  Disposition::orderBy('name')->get())
            ->assertSeeLivewire('timy::teams-table')
            ->assertSeeLivewire('timy::role-management')
            ->assertSeeLivewire('timy::forced-timer-management');
    }

    /** @test */
    public function authorized_users_can_see_admin_dashboard()
    {
        $user = $this->adminUser();

        $this->actingAs($user)
            ->get(route('admin_dashboard'))
            ->assertOk()
            ->assertViewIs('timy::dashboards.admin')
            ->assertViewHas('users')
            ->assertSeeLivewire('timy::open-timers-monitor');
    }

    /** @test */
    public function authorized_users_can_see_users_dashboard()
    {

        $this->actingAs($this->timyUser());

        $response = $this->get(route('user_dashboard'))
            ->assertOk()
            ->assertViewIs('timy::dashboards.user')
            ->assertViewHas('chart')
            ->assertSeeLivewire('timy::user-hours-info')
            ->assertSeeLivewire('timy::timers-table');

        $this->assertTrue($response->viewData('chart') instanceof UserDailyHoursChart);
    }
}
