<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Tests\TestCase;

class CloseInactiveTimersCommandTest extends TestCase
{
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
        $ip_address = '142.250.9.139'; //google
        factory(Timer::class)->create(['ip_address' => $ip_address, 'finished_at' => null]);

        $this->assertCount(1, Timer::running()->get());

        $this->artisan('timy:close-inactive-timers')
            ->assertExitCode(0);

        $this->assertCount(1, Timer::running()->get());
    }
}
