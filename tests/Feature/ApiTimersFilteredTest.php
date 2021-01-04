<?php

namespace Dainsys\Timy\Tests\Feature;

use Dainsys\Timy\Models\Disposition;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Tests\TestCase;

class ApiTimersFilteredTest extends TestCase
{
    /** @test */
    public function it_return_hours_for_current_date()
    {
        $disposition = Disposition::factory()->create(['payable' => 1]);
        $user = $this->user();
        $timer = Timer::factory()->create([
            'user_id' => $user->id,
            'name' => $user->name,
            'disposition_id' => $disposition->id,
            'started_at' => now()->endOfDay()->subHours(2),
            'finished_at' => now()->endOfDay()
        ]);

        $response = $this->actingAs($this->superAdminUser())
            ->get(route('timy.timers_filtered'));

        $response->assertJson([
            'data' => [
                [
                    "user_id" => $user->id,
                    "name" => $user->name,
                    "disposition" => $disposition->name,
                    "total_hours" => 2,
                    "payable_hours" => 2,
                ]
            ]
        ]);
    }

    /** @test */
    public function it_return_hours_between_given_date_and_current_date()
    {
        $disposition = Disposition::factory()->create(['payable' => 1]);
        $user = $this->user();
        $anotherUser = $this->user();
        $fromDate = now();
        $withinRange = Timer::factory()->create([
            'user_id' => $user->id,
            'name' => $user->name,
            'disposition_id' => $disposition->id,
            'started_at' => $fromDate,
            'finished_at' => now()->endOfDay()
        ]);
        $outOfRange = Timer::factory()->create([
            'user_id' => $anotherUser->id,
            'name' => $anotherUser->name,
            'disposition_id' => $disposition->id,
            'started_at' => now()->subDays(3),
            'finished_at' => now()->endOfDay()
        ]);

        $response = $this->actingAs($this->superAdminUser())
            ->get(route('timy.timers_filtered', ['from_date' => $fromDate->format('Y-m-d')]));

        $response
            ->assertJsonCount(1, [
                'data'
            ])
            ->assertJson([
                'data' => [
                    [
                        "user_id" => $withinRange->id,
                        "name" => $withinRange->name,
                    ]
                ]
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        "user_id" => $outOfRange->id,
                        "name" => $outOfRange->name,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_return_hours_between_from_date_and_to_date()
    {
        $disposition = Disposition::factory()->create(['payable' => 1]);
        $user = $this->user();
        $anotherUser = $this->user();
        $fromDate = now()->subDays(1);
        $withinRange = Timer::factory()->create([
            'user_id' => $user->id,
            'name' => $user->name,
            'disposition_id' => $disposition->id,
            'started_at' => $fromDate,
            'finished_at' => $fromDate
        ]);
        $beforeRange = Timer::factory()->create([
            'user_id' => $anotherUser->id,
            'name' => $anotherUser->name,
            'disposition_id' => $disposition->id,
            'started_at' => now()->subDays(3),
            'finished_at' => now()->endOfDay()
        ]);
        $afterRange = Timer::factory()->create([
            'user_id' => $anotherUser->id,
            'name' => $anotherUser->name,
            'disposition_id' => $disposition->id,
            'started_at' => now(),
            'finished_at' => now()->endOfDay()
        ]);

        $response = $this->actingAs($this->superAdminUser())
            ->get(route('timy.timers_filtered', [
                'from_date' => $fromDate->format('Y-m-d'),
                'to_date' => $fromDate->format('Y-m-d'),
            ]));

        $response
            ->assertJsonCount(1, [
                'data'
            ])
            ->assertJson([
                'data' => [
                    [
                        "user_id" => $withinRange->id,
                        "name" => $withinRange->name,
                    ]
                ]
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        "user_id" => $beforeRange->id,
                        "name" => $beforeRange->name,
                    ]
                ]
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        "user_id" => $afterRange->id,
                        "name" => $afterRange->name,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_return_hours_filtered_by_disposition()
    {
        $disposition = Disposition::factory()->create(['payable' => 1]);
        $anotherDisposition = Disposition::factory()->create(['payable' => 1]);

        Timer::factory()->create([
            'disposition_id' => $disposition->id,
        ]);
        Timer::factory()->create([
            'disposition_id' => $anotherDisposition->id,
        ]);

        $response = $this->actingAs($this->superAdminUser())
            ->get(route('timy.timers_filtered', [
                'disposition' => $disposition->name,
            ]));

        $response
            ->assertJsonCount(1, [
                'data'
            ])
            ->assertJson([
                'data' => [
                    [
                        'disposition' => $disposition->name,
                    ]
                ]
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        'disposition' => $anotherDisposition->name,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_return_hours_filtered_by_user()
    {
        $user = $this->user();
        $anotherUser = $this->user();

        Timer::factory()->create([
            'user_id' => $user->id,
        ]);
        Timer::factory()->create([
            'user_id' => $anotherUser->id,
        ]);

        $response = $this->actingAs($this->superAdminUser())
            ->get(route('timy.timers_filtered', [
                'user' => $user->name,
            ]));

        $response
            ->assertJsonCount(1, [
                'data'
            ])
            ->assertJson([
                'data' => [
                    [
                        'name' => $user->name,
                    ]
                ]
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        'name' => $anotherUser->name,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_return_hours_filtered_by_running_timers()
    {
        $user = $this->user();
        $runningTimerUser = $this->user();

        Timer::factory()->create([
            'user_id' => $user->id,
            'finished_at' => now(),
        ]);
        Timer::factory()->create([
            'user_id' => $runningTimerUser->id,
            'finished_at' => null,
        ]);

        $response = $this->actingAs($this->superAdminUser())
            ->get(route('timy.timers_filtered', [
                'running' => true,
            ]));

        $response
            ->assertJsonCount(1, [
                'data'
            ])
            ->assertJson([
                'data' => [
                    [
                        'name' => $runningTimerUser->name,
                    ]
                ]
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        'name' => $user->name,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_return_hours_filtered_by_payables()
    {
        $payable = Disposition::factory()->create(['payable' => 1]);
        $notPayable = Disposition::factory()->create(['payable' => 0]);

        Timer::factory()->create([
            'disposition_id' => $payable->id,
        ]);
        Timer::factory()->create([
            'disposition_id' => $notPayable->id,
        ]);

        $response = $this->actingAs($this->superAdminUser())
            ->get(route('timy.timers_filtered', [
                'payable' => true,
            ]));

        $response
            ->assertJsonCount(1, [
                'data'
            ])
            ->assertJson([
                'data' => [
                    [
                        'disposition' => $payable->name,
                    ]
                ]
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        'disposition' => $notPayable->name,
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_return_hours_filtered_by_invoiceables()
    {
        $invoiceable = Disposition::factory()->create(['invoiceable' => 1]);
        $notInvoiceable = Disposition::factory()->create(['invoiceable' => 0]);

        Timer::factory()->create([
            'disposition_id' => $invoiceable->id,
        ]);
        Timer::factory()->create([
            'disposition_id' => $notInvoiceable->id,
        ]);

        $response = $this->actingAs($this->superAdminUser())
            ->get(route('timy.timers_filtered', [
                'invoiceable' => true,
            ]));

        $response
            ->assertJsonCount(1, [
                'data'
            ])
            ->assertJson([
                'data' => [
                    [
                        'disposition' => $invoiceable->name,
                    ]
                ]
            ])
            ->assertJsonMissing([
                'data' => [
                    [
                        'disposition' => $notInvoiceable->name,
                    ]
                ]
            ]);
    }
}
