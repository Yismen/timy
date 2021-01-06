<?php

namespace Dainsys\Timy\Tests\Feature;

use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Dainsys\Timy\Notifications\UsersWithTooManyHours;

class UsersWithTooManyHoursTest extends TestCase
{
    public float $threshold;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->threshold = config('timy.daily_hours_threshold');
    }

    /** @test */
    public function it_sends_a_notification_if_users_have_passed_the_daily_hours_threshold()
    {
        $user = $this->user();
        $timyUser = $this->timyUser();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();
        $timer = Timer::factory()->payable()->create([
            'started_at' => now()->subMinutes(($this->threshold * 60) + 50),
            'finished_at' => now(),
            'user_id' => $timyUser->id,
        ]);

        $this->artisan('timy:users-with-too-many-hours')
            ->assertExitCode(0);

        Notification::assertSentTo([$adminUser, $superAdminUser], UsersWithTooManyHours::class);
        Notification::assertNotSentTo([$user, $timyUser], UsersWithTooManyHours::class);
    }

    /** @test */
    public function it_sends_a_notification_only_hours_are_payable()
    {
        $user = $this->user();
        $timyUser = $this->timyUser();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();
        $notPayableHours = Timer::factory()->notPayable()->create([
            'started_at' => now()->subMinutes(($this->threshold * 60) + 50),
            'finished_at' => now(),
            'user_id' => $timyUser->id,
        ]);

        $this->artisan('timy:users-with-too-many-hours')
            ->assertExitCode(0);

        Notification::assertNothingSent();
    }
    /** @test */
    public function it_only_notify_admin_and_super_admin_users()
    {
        $user = $this->user();
        $timyUser = $this->timyUser();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();
        $timer = Timer::factory()->payable()->create([
            'started_at' => now()->subMinutes(($this->threshold * 60) + 50),
            'finished_at' => now(),
            'user_id' => $timyUser->id,
        ]);

        $this->artisan('timy:users-with-too-many-hours');

        Notification::assertSentTo([$adminUser, $superAdminUser], UsersWithTooManyHours::class);
        Notification::assertNotSentTo([$user, $timyUser], UsersWithTooManyHours::class);
    }

    /** @test */
    public function it_only_sends_notifications_if_hours_pass_the_threshold()
    {
        $user = $this->user();
        $timyUser = $this->timyUser();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();
        $untherTheTreshold = Timer::factory()->payable()->create([
            'started_at' => now()->subMinutes(($this->threshold * 60) - 50),
            'finished_at' => now(),
            'user_id' => $timyUser->id,
        ]);

        $this->artisan('timy:users-with-too-many-hours');

        Notification::assertNothingSent();
    }
}
