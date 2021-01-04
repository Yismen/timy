<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Models\Team;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class TeamTest extends TestCase
{
    /** @test */
    public function it_loads_relationships()
    {
        $disposition = Team::factory()->create();

        $disposition->with(['users'])->first();

        $this->assertInstanceOf(Collection::class, $disposition->users);
    }
}
