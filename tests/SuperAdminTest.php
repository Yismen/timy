<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\App\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuperAdminTest extends TestCase
{
    // use RefreshDatabase;

    public $user;

    /**
     * Executed before each test.
     */

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function guest_are_unauthorized()
    {
        $this->get(route('timy_super_admin'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function unauthorized_users_should_not_see_dashboard()
    {
        $this->actingAs(factory(config('timy.models.user'))->create())
            ->get(route('timy_super_admin'))
            ->assertStatus(403);
    }

    /** @test */
    public function super_admin_users_can_see_dashboard()
    {
        $this->actingAs($this->superAdmin())
            ->get(route('timy_super_admin'))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'roles' => [
                        [
                            'users' => []
                        ]
                    ],
                    'unassigned' => []
                ]
            ]);
    }

    /** @test */
    public function unauthorized_users_should_be_able_to_perform_asignations()
    {
        $user = factory(config('timy.models.user'))->create();
        $role = factory(Role::class)->create();

        $this->actingAs($user)
            ->post(route('timy_assign_user_role', ['user' => $user->id, 'role' => $role->id]))
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_be_assigned()
    {
        $user = factory(config('timy.models.user'))->create();
        $role = factory(Role::class)->create();

        $this->actingAs($this->superAdmin())
            ->post(route('timy_assign_user_role', ['user' => $user->id, 'role' => $role->id]))
            ->assertJson([
                'data' => [
                    'name' => $user->name,
                    'timy_role_id' => $role->id,
                    'timy_role' => [
                        'name' => $role->name
                    ]
                ]
            ]);
    }

    protected function superAdmin()
    {
        return factory(config('timy.models.user'))->create(['email' => config('timy.super_admin.email')]);
    }
}
