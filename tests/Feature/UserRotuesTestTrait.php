<?php

namespace Dainsys\Timy\Tests\Feature;

use Dainsys\Timy\Models\Disposition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

trait UserRotuesTestTrait
{
    /** @test */
    public function it_closes_user_session_when_it_leave_the_browser()
    {
        $user = $this->user();
        $disposition = factory(Disposition::class)->create();
        $user->startTimer($disposition->id, ['forced' => true]);

        $this->assertEquals($user->timers()->first()->finished_at, null);

        $this->actingAs($user)
            ->get(route('timy_timers.user_left', $user->id))
            ->assertOk();

        $this->assertNotEquals($user->timers()->first()->finished_at, null);
    }

    /** @test */
    public function it_pings_user_and_keeps_session_if_user_is_active()
    {
        $user = $this->user();
        $disposition = factory(Disposition::class)->create();
        $user->startTimer($disposition->id, ['forced' => true]);

        $this->actingAs($user)
            ->get(route('timy_ping_user'))
            ->assertOk();

        $this->assertEquals($user->timers()->first()->finished_at, null);
    }

    /** @test */
    public function it_pings_user_and_return_error_if_not_logged_in()
    {
        $user = $this->user();
        $disposition = factory(Disposition::class)->create();
        $user->startTimer($disposition->id, ['forced' => true]);
        $this->actingAs($user);
        auth()->logout();

        $this->get(route('timy_ping_user'))
            ->assertRedirect(route('login'));

        $this->assertNotEquals($user->timers()->first()->finished_at, null);
    }
}
