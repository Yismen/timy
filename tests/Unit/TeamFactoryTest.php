<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Models\Team;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class TeamFactoryTest extends TestCase
{
    /** @test */
    public function it_creates_an_instance_of_team_model()
    {
        $team = Team::factory()->create();

        $this->assertInstanceOf(Team::class, $team);
    }
}
