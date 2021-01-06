<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Repositories\Hours\HoursBaseRepository;
use Dainsys\Timy\Repositories\Hours\TotalToday;
use Dainsys\Timy\Tests\TestCase;

class TotalTodayRepositoryTest extends TestCase
{
    /** @test */
    public function total_today_repo_returns_correct_instances()
    {
        $repo = new TotalToday();

        $this->assertInstanceOf(HoursBaseRepository::class, $repo);
    }
    /** @test */
    public function total_today_repo_returns_hours_for_timy_users_only()
    {
        $today = now();
        $user = $this->user();
        $timyUser = $this->timyUser();
        Timer::factory()->create([
            'started_at' => $today->copy()->subHour(),
            'finished_at' => $today->copy(),
            'user_id' => $user->id
        ]);
        Timer::factory()->create([
            'started_at' => $today->copy()->subHour(),
            'finished_at' => $today->copy(),
            'user_id' => $timyUser->id
        ]);

        $repo = (new TotalToday())->getTotal();

        $this->assertEquals(1, $repo->sum('total_hours'));
    }

    /** @test */
    public function total_today_repo_returns_a_total_of_todays_user_hours()
    {
        $today = now();
        $yesterday = now()->subDay();
        $todayUser = $this->timyUser();
        $yesterdayUser = $this->timyUser();
        Timer::factory()->create([
            'started_at' => $yesterday->copy()->subHour(),
            'finished_at' => $yesterday->copy(),
            'user_id' => $yesterdayUser->id
        ]);
        Timer::factory()->create([
            'started_at' => $today->copy()->subHour(),
            'finished_at' => $today->copy(),
            'user_id' => $todayUser->id
        ]);

        $repo = (new TotalToday())->getTotal();
        $this->assertEquals(1, $repo->where('name', $todayUser->name)->sum('total_hours'));
        $this->assertEquals(0, $repo->where('name', $yesterdayUser->name)->sum('total_hours'));
    }

    /** @test */
    public function total_today_repo_returns_hours_over_threshold()
    {
        $today = now();
        $threshold = config('timy.daily_hours_threshold');
        $overThresholdUser = $this->timyUser();
        $underThresholdUser = $this->timyUser();
        Timer::factory()->create([
            'started_at' => $today->copy()->subMinutes(($threshold + 1) * 60),
            'user_id' => $overThresholdUser->id
        ]);
        Timer::factory()->create([
            'started_at' => $today->copy()->subMinutes(($threshold - 1) * 60),
            'user_id' => $underThresholdUser->id
        ]);

        $repo = (new TotalToday())->overDailyThreshold();

        $this->assertCount(1, $repo);
        $this->assertEquals($threshold + 1, $repo->sum('total_hours'));
        $this->assertContains($overThresholdUser->name, $repo->pluck('name'));
        $this->assertNotContains($underThresholdUser->name, $repo->pluck('name'));
    }
}
