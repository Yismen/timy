<?php

namespace Dainsys\Timy\Tests\Feature\Traits;

use Dainsys\Timy\Repositories\DispositionsRepository;
use Dainsys\Timy\Models\Role;
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
        $user =  $this->user(['email' => config('timy.super_admin_email')]);

        $this->actingAs($user)
            ->get(route('super_admin_dashboard'))
            ->assertOk()
            ->assertViewIs('timy::dashboards.super-admin')
            ->assertViewHas('dispositions',  DispositionsRepository::all());
    }

    /** @test */
    public function authorized_users_can_see_admin_dashboard()
    {
        $role = Role::where('name', config('timy.roles.admin'))->first(); //created at the migration
        $user =  $this->user();
        $user->assignTimyRole($role);

        $this->actingAs($user)
            ->get(route('admin_dashboard'))
            ->assertOk()
            ->assertViewIs('timy::dashboards.admin')
            ->assertViewHas('users');
    }

    /** @test */
    public function authorized_users_can_see_users_dashboard()
    {
        $this->withoutExceptionHandling();
        $role = Role::where('name', config('timy.roles.user'))->first(); //created at the migration
        $user =  $this->user();
        $user->assignTimyRole($role);

        $this->actingAs($user)
            ->get(route('user_dashboard'))
            ->assertOk()
            ->assertViewIs('timy::dashboards.user');
    }
}
