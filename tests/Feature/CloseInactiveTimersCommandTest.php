<?php

namespace Dainsys\Timy\Tests\Feature;

use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class CloseInactiveTimersCommandTest extends TestCase
{
    /** @test */
    public function it_closes_invalid_ips()
    {
        Timer::factory()->create(['ip_address' => 'invalid ip address', 'finished_at' => null]);

        $this->assertCount(1, Timer::running()->get());

        $this->artisan('timy:close-inactive-timers')
            ->assertExitCode(0);

        $this->assertCount(0, Timer::running()->get());
    }

    /** @test */
    public function it_keeps_valid_ips()
    {
        Http::fake();
        $ip_address = '142.250.9.139'; //google
        Timer::factory()->create(['ip_address' => $ip_address, 'finished_at' => null]);

        $this->assertCount(1, Timer::running()->get());

        $this->artisan('timy:close-inactive-timers')
            ->assertExitCode(0);

        $this->assertCount(1, Timer::running()->get());
    }
}
