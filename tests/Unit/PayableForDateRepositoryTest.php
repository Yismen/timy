<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Repositories\Hours\HoursBaseRepository;
use Dainsys\Timy\Repositories\Hours\PayableForDate;
use Dainsys\Timy\Tests\TestCase;

class PayableForDateRepositoryTest extends TestCase
{
    /** @test */
    public function payable_for_date_repo_returns_correct_instances()
    {
        $repo = new PayableForDate();

        $this->assertInstanceOf(HoursBaseRepository::class, $repo);
    }
    /** @test */
    public function payable_for_date_repo_returns_payable_hours_only()
    {
        $today = now();
        $payableUser = $this->timyUser();
        $notPayableUser = $this->timyUser();
        Timer::factory()->payable()->create([
            'started_at' => $today->copy()->subHour(),
            'finished_at' => $today->copy(),
            'user_id' => $payableUser->id
        ]);
        Timer::factory()->notPayable()->create([
            'started_at' => $today->copy()->subHour(),
            'finished_at' => $today->copy(),
            'user_id' => $notPayableUser->id
        ]);

        $repo = (new PayableForDate())->getTotal();

        $this->assertEquals(1, $repo->sum('total_hours'));
        $this->assertEquals(1, $repo->where('name', $payableUser->name)->sum('total_hours'));
        $this->assertEquals(0, $repo->where('name', $notPayableUser->name)->sum('total_hours'));
    }

    /** @test */
    public function payable_for_date_repo_returns_a_total_of_yesterday_user_hours()
    {
        $today = now();
        $yesterday = now()->subDay();
        $todayUser = $this->timyUser();
        $yesterdayUser = $this->timyUser();
        Timer::factory()->payable()->create([
            'started_at' => $yesterday->copy()->subHour(),
            'finished_at' => $yesterday->copy(),
            'user_id' => $yesterdayUser->id
        ]);
        Timer::factory()->payable()->create([
            'started_at' => $today->copy()->subHour(),
            'finished_at' => $today->copy(),
            'user_id' => $todayUser->id
        ]);

        $repo = (new PayableForDate($yesterday))->getTotal();
        $this->assertEquals(1, $repo->where('name', $yesterdayUser->name)->sum('total_hours'));
        $this->assertEquals(0, $repo->where('name', $todayUser->name)->sum('total_hours'));
    }

    /** @test */
    public function payable_for_date_repo_returns_hours_over_threshold()
    {
        $today = now();
        $threshold = config('timy.daily_hours_threshold');
        $overThresholdUser = $this->timyUser();
        $underThresholdUser = $this->timyUser();
        Timer::factory()->payable()->create([
            'started_at' => $today->copy()->subMinutes(($threshold + 1) * 60),
            'user_id' => $overThresholdUser->id
        ]);
        Timer::factory()->payable()->create([
            'started_at' => $today->copy()->subMinutes(($threshold - 1) * 60),
            'user_id' => $underThresholdUser->id
        ]);

        $repo = (new PayableForDate())->overDailyThreshold();

        $this->assertCount(1, $repo);
        $this->assertEquals($threshold + 1, $repo->sum('total_hours'));
        $this->assertContains($overThresholdUser->name, $repo->pluck('name'));
        $this->assertNotContains($underThresholdUser->name, $repo->pluck('name'));
    }
}
