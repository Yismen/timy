<?php

namespace Dainsys\Timy\Tests\Unit;

use App\User;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class UserFactoryTest extends TestCase
{
    /** @test */
    public function it_creates_an_instance_of_team_model()
    {
        $team = User::factory()->create();

        $this->assertInstanceOf(User::class, $team);
    }
}
