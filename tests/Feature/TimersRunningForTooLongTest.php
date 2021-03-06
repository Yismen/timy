<?php

namespace Dainsys\Timy\Tests\Feature;

use App\User;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Dainsys\Timy\Notifications\TimersRunningForTooLong;

class TimersRunningForTooLongTest extends TestCase
{
    public float $threshold;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->threshold = config('timy.running_timers_threshold');
    }

    /** @test */
    public function it_sends_a_notification_with_if_timer_is_running_for_too_long()
    {
        $regularUser = $this->user();
        $timyUser = $this->timyUser();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();
        $tooManyHours = Timer::factory()->payable()->count(2)->create([
            'started_at' => now()->subMinutes($this->threshold + $minutes = 10),
            'user_id' => $timyUser->id
        ]);

        $this->artisan('timy:timers-running-for-too-long')
            ->assertExitCode(0);

        Notification::assertTimesSent(2, TimersRunningForTooLong::class);
        Notification::assertSentTo([$adminUser, $superAdminUser], TimersRunningForTooLong::class);
    }

    /** @test */
    public function it_does_not_notify_if_timer_is_not_running()
    {
        $regularUser = $this->user();
        $timyUser = $this->timyUser();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();
        $tooManyHours = Timer::factory()->payable()->closed()->create([
            'started_at' => now()->subMinutes($this->threshold + $minutes = 10),
            'user_id' => $timyUser->id
        ]);

        $this->artisan('timy:timers-running-for-too-long');

        Notification::assertNothingSent();
    }
    /** @test */
    public function it_only_notify_admin_and_super_admin_users()
    {
        $user = $this->user();
        $timyUser = $this->user();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();
        $tooManyHours = Timer::factory()->payable()->count(2)->create([
            'started_at' => now()->subMinutes($this->threshold + $minutes = 10),
            'user_id' => $timyUser->id
        ]);

        $this->artisan('timy:timers-running-for-too-long');

        Notification::assertTimesSent(2, TimersRunningForTooLong::class);
        Notification::assertSentTo([$adminUser, $superAdminUser], TimersRunningForTooLong::class);
        Notification::assertNotSentTo([$user, $timyUser], TimersRunningForTooLong::class);
    }

    /** @test */
    public function it_send_the_notification_only_if_user_passes_the_threshold()
    {
        $this->adminUser();
        $this->superAdminUser();
        $notifyableUsers = User::isTimyAdmin()->get();

        $runningTimersShortly = Timer::factory()->count(2)->create([
            'started_at' => now()->subMinutes(
                $this->threshold - $minutes = 10
            ),
            'finished_at' => null
        ]);

        $this->artisan('timy:timers-running-for-too-long');
        Notification::assertNothingSent();
    }

    /** @test */
    public function it_send_the_notification_only_on_payable_timers()
    {
        $user = $this->user();
        $timyUser = $this->user();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();
        $tooManyHours = Timer::factory()->payable()->running()->count(2)->create([
            'started_at' => now()->subMinutes($this->threshold + $minutes = 10),
            'user_id' => $timyUser->id
        ]);

        $this->artisan('timy:timers-running-for-too-long');

        Notification::assertTimesSent(2, TimersRunningForTooLong::class);
    }

    /** @test */
    public function it_does_not_send_the_notification_if_running_timer_is_not_payable()
    {
        $user = $this->user();
        $timyUser = $this->user();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();
        $tooManyHours = Timer::factory()->notPayable()->running()->count(2)->create([
            'started_at' => now()->subMinutes($this->threshold + $minutes = 10),
            'user_id' => $timyUser->id
        ]);

        $this->artisan('timy:timers-running-for-too-long');

        Notification::assertNothingSent();
    }
}
