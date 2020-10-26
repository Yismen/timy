<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Tests\Mocks\UserMockery;
use Dainsys\Timy\Tests\TestCase;
use Dainsys\Timy\Timer;

class CloseInactiveTimersCommandTest extends TestCase
{
    /** @test */
    public function it_runs_the_command()
    {
        $this->artisan('timy:close-inactive-timers')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_closes_invalid_ips()
    {
        factory(Timer::class)->create(['ip_address' => 'invalid ip address', 'finished_at' => null]);

        $this->assertCount(1, Timer::running()->get());

        $this->artisan('timy:close-inactive-timers')
            ->assertExitCode(0);

        $this->assertCount(0, Timer::running()->get());
    }

    /** @test */
    public function it_keeps_valid_ips()
    {
        factory(Timer::class)->create(['ip_address' => '200.88.117.182', 'finished_at' => null]);

        $this->assertCount(1, Timer::running()->get());

        $this->artisan('timy:close-inactive-timers')
            ->assertExitCode(0);

        $this->assertCount(1, Timer::running()->get());
    }
}
