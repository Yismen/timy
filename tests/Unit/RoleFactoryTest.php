<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Models\Role;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class RoleFactoryTest extends TestCase
{
    /** @test */
    public function it_creates_an_instance_of_role_model()
    {
        $role = Role::factory()->create();

        $this->assertInstanceOf(Role::class, $role);
    }
}
