<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class TimerFactoryTest extends TestCase
{
    /** @test */
    public function it_creates_an_instance_of_timer_model()
    {
        $timer = Timer::factory()->create();

        $this->assertInstanceOf(Timer::class, $timer);
    }
    /** @test */
    public function it_creates_a_running_timer()
    {
        $timer = Timer::factory()->running()->create();

        $this->assertNull($timer->finished_at);
    }
    /** @test */
    public function it_creates_a_closed_timer()
    {
        $timer = Timer::factory()->closed()->create();

        $this->assertNotNull($timer->finished_at);
    }
    /** @test */
    public function it_creates_a_closed_timer_and_accepts_a_date()
    {
        $carbon = now()->subMinutes(153);

        $timer = Timer::factory()->closed($carbon)->create();

        $this->assertNotNull($timer->finished_at);
        $this->assertEquals($timer->finished_at->format('Y-m-d H:i:s'), $carbon->format('Y-m-d H:i:s'));
    }
}
