<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Models\Role;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class RoleTest extends TestCase
{
    /** @test */
    public function it_loads_relationships()
    {
        $disposition = Role::factory()->create();

        $disposition->with(['users'])->first();

        $this->assertInstanceOf(Collection::class, $disposition->users);
    }
}
