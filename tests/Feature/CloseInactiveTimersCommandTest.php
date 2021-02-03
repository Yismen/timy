<?php

namespace Dainsys\Timy\Tests\Feature;

use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class CloseInactiveTimersCommandTest extends TestCase
{
    /** @test */
    public function it_closes_invalid_ips()
    {
        Config::set('timy.commands.close-inactive-timers', true);
        Timer::factory()->create(['ip_address' => 'invalid ip address', 'finished_at' => null]);

        $this->assertCount(1, Timer::running()->get());

        $this->artisan('timy:close-inactive-timers')
            ->assertExitCode(0);

        $this->assertCount(0, Timer::running()->get());
    }

    /** @test */
    public function it_only_runs_if_config_is_set_to_true()
    {
        Config::set('timy.commands.close-inactive-timers', false);
        Timer::factory()->create(['ip_address' => 'invalid ip address', 'finished_at' => null]);

        $this->assertCount(1, Timer::running()->get());

        $this->artisan('timy:close-inactive-timers')
            ->assertExitCode(0);

        $this->assertCount(1, Timer::running()->get());
    }

    /** @test */
    public function it_keeps_valid_ips()
    {
        Config::set('timy.with_scheduled_commands', true);
        Http::fake();
        $ip_address = '142.250.9.139'; //google
        Timer::factory()->create(['ip_address' => $ip_address, 'finished_at' => null]);

        $this->assertCount(1, Timer::running()->get());

        $this->artisan('timy:close-inactive-timers')
            ->assertExitCode(0);

        $this->assertCount(1, Timer::running()->get());
    }
}
