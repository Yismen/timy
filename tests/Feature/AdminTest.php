<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\Disposition;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Role;
use Dainsys\Timy\Timer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuperAdminTest extends TestCase
{
    // use RefreshDatabase;

    public $user;

    /** @test */
    public function guest_are_unauthorized()
    {
        $this->get(route('timy_admin'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function unauthorized_users_should_not_see_dashboard()
    {
        $this->actingAs(factory(config('timy.models.user'))->create())
            ->get(route('timy_admin'))
            ->assertStatus(403);
    }

    /** @test */
    public function admin_users_can_see_dashboard()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user());
        $timers = TimerResource::collection(factory(Timer::class, 5)->create());

        $this->actingAs($this->adminUser())
            ->get(route('timy_admin'))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'dispositions' => Disposition::all()->toArray(),
                    'running_timers' => [],
                ]
            ]);
    }

    /** @test */
    public function unauthorized_users_should_not_create_timers_forced()
    {
        $user = factory(config('timy.models.user'))->create();
        $disposition = factory(Disposition::class)->create();

        $this->actingAs($user)
            ->post(route('timy_admin.create_timer_forced', ['user' => $user->id, 'disposition' => $disposition->id]))
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_should_create_timers_forced()
    {
        $user = factory(config('timy.models.user'))->create();
        $disposition = factory(Disposition::class)->create();

        $this->actingAs($this->adminUser())
            ->post(route('timy_admin.create_timer_forced', ['user' => $user->id, 'disposition' => $disposition->id]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'user_id' => $user->id,
                    'disposition_id' => $disposition->id
                ]
            ]);
    }

    protected function adminUser()
    {
        $role = Role::where('name', config('timy.roles.admin'))->first(); //created at the migration
        $user = factory(config('timy.models.user'))->create();
        $user->assignTimyRole($role);

        return $user;
    }
}
