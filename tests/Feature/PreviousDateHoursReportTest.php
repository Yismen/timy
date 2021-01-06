<?php

namespace Dainsys\Timy\Tests\Feature;

use App\User;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Dainsys\Timy\Notifications\PreviousDateHoursReport;

class PreviousDateHoursReportTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    /** @test */
    public function it_sends_a_previous_day_hours_report()
    {
        $this->user();
        $timyUser = $this->timyUser();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();

        $timers = Timer::factory()->payable()->count(2)->create([
            'started_at' => now()->subDay()->subHour(),
            'finished_at' => now()->subDay(),
            'user_id' => $timyUser->id
        ]);

        $this->artisan('timy:previous-date-hours-report')
            ->assertExitCode(0);
        Notification::assertTimesSent(2, PreviousDateHoursReport::class);
    }

    /** @test */
    public function it_only_report_hours_for_previous_date()
    {
        $this->user();
        $timyUser = $this->timyUser();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();

        $twoDaysAgoHours = Timer::factory()->payable()->create([
            'started_at' => now()->subDays(2)->subHours(1),
            'finished_at' => now()->subDay(),
            'user_id' => $timyUser->id
        ]);

        $todayHours = Timer::factory()->payable()->create([
            'started_at' => now()->subHours(2),
            'finished_at' => now(),
            'user_id' => $timyUser->id
        ]);

        $this->artisan('timy:previous-date-hours-report')
            ->assertExitCode(0);
        Notification::assertTimesSent(0, PreviousDateHoursReport::class);
    }

    /** @test */
    public function it_only_notify_admin_and_super_admin_users()
    {
        $user = $this->user();
        $timyUser = $this->timyUser();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();

        $timers = Timer::factory()->payable()->count(2)->create([
            'started_at' => now()->subDay()->subHour(),
            'finished_at' => now()->subDay(),
            'user_id' => $timyUser->id
        ]);

        $this->artisan('timy:previous-date-hours-report');

        Notification::assertTimesSent(2, PreviousDateHoursReport::class);
        Notification::assertSentTo([$adminUser, $superAdminUser], PreviousDateHoursReport::class);
        Notification::assertNotSentTo([$user, $timyUser], PreviousDateHoursReport::class);
    }

    /** @test */
    public function it_is_sent_only_if_users_have_payable_hours()
    {
        $this->user();
        $timyUser = $this->timyUser();
        $adminUser = $this->adminUser();
        $superAdminUser = $this->superAdminUser();

        $zeHoursTimer = Timer::factory()->payable()->create([
            'started_at' => now()->subDay(),
            'finished_at' => now()->subDay(),
            'user_id' => $timyUser->id
        ]);

        $notPayableHours = Timer::factory()->notPayable()->create([
            'started_at' => now()->subDay()->subHours(2),
            'finished_at' => now()->subDay(),
            'user_id' => $timyUser->id
        ]);

        $this->artisan('timy:previous-date-hours-report')
            ->assertExitCode(0);
        Notification::assertTimesSent(0, PreviousDateHoursReport::class);
    }
}
