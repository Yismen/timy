<?php

namespace Dainsys\Timy\Tests\Unit;

use App\User;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Dainsys\Timy\Notifications\TimersRunningForTooLong;

class TimersRunningForTooLongTest extends TestCase
{
    public $threshold;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->threshold = config('timy.running_timers_threshold');
    }

    /** @test */
    public function itGrabsAllActiveTimersAndNotifyIfTheyHavePassedTheHoursThresshold()
    {
        $this->adminUser();
        $this->superAdminUser();
        $notifyableUsers = User::isTimyAdmin()->get();

        $runningTimers = factory(Timer::class, 2)->create([
            'started_at' => now()->subMinutes(
                $this->threshold + $minutes = 10
            ),
            'finished_at' => null
        ]);

        $this->assertCount(2, Timer::running()->get());

        $this->artisan('timy:timers-running-for-too-long')
            ->assertExitCode(0);

        Notification::assertSentTo(
            [$notifyableUsers],
            TimersRunningForTooLong::class
        );

        Notification::assertTimesSent(
            $notifyableUsers->count(),
            TimersRunningForTooLong::class
        );

        $this->assertCount(2, Timer::running()->get());
    }
    /** @test */
    public function itDoesNotNotifyUnauthorizedUsers()
    {
        $user = $this->user();
        $this->superAdminUser();

        $notifyableUsers = User::isTimyAdmin()->get();

        $runningTimers = factory(Timer::class, 2)->create([
            'started_at' => now()->subMinutes(
                $this->threshold + $minutes = 10
            ),
            'finished_at' => null
        ]);

        $this->artisan('timy:timers-running-for-too-long');

        Notification::assertTimesSent(
            $notifyableUsers->count(),
            TimersRunningForTooLong::class
        );

        Notification::assertNotSentTo(
            [$user],
            TimersRunningForTooLong::class
        );
    }

    /** @test */
    public function itDoesNotNotifyIfTimersIsRunningForLessThanTheThreshold()
    {
        $this->adminUser();
        $this->superAdminUser();
        $notifyableUsers = User::isTimyAdmin()->get();

        $runningTimersShortly = factory(Timer::class, 2)->create([
            'started_at' => now()->subMinutes(
                $this->threshold - $minutes = 10
            ),
            'finished_at' => null
        ]);

        $this->artisan('timy:timers-running-for-too-long');

        Notification::assertNothingSent(
            [$notifyableUsers],
            TimersRunningForTooLong::class
        );
    }
}
