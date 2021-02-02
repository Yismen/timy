<?php

namespace Dainsys\Timy\Tests\Feature;

use App\User;
use Carbon\Carbon;
use Dainsys\Timy\Http\Livewire\ForcedTimerManagement;
use Dainsys\Timy\Models\Disposition;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class ForcedTimersTest extends TestCase
{
    /** @test */
    public function properties_are_set_on_load()
    {
        Livewire::test('timy::forced-timer-management')
            ->assertViewIs('timy::livewire.forced-timer-management')
            ->assertViewHas('users', null)
            ->assertViewHas('dispositions', Disposition::orderBy('name')->get());
    }

    /** @test */
    public function forced_timers_view_has_key_words()
    {
        $this->timyUser([], 2);
        $users = $this->usersWithTimers();

        Livewire::test(ForcedTimerManagement::class)
            ->assertSee('getUsers')
            ->assertSet('users', $users)
            ->set('selected', $users->pluck('id')->toArray())
            ->assertSee('closeForm')
            ->assertSee('createForcedTimers');
    }

    /** @test */
    public function it_reset_vars_on_form_close()
    {
        Livewire::test('timy::forced-timer-management')
            ->call('closeForm')
            ->assertSet('selected', [])
            ->assertSet('selectedDisposition', null);
    }

    /** @test */
    public function it_creates_forced_timers()
    {
        $user = $this->user();
        $disposition = Disposition::factory()->create();

        Livewire::test('timy::forced-timer-management')
            ->set('selected', [$user->id])
            ->call('createForcedTimers')
            ->assertSet('selectedDisposition', null)
            ->set('selectedDisposition', null)
            ->assertHasErrors('selectedDisposition', 'required')
            ->set('selectedDisposition', 345)
            ->assertHasErrors('selectedDisposition', 'exists')
            ->set('selectedDisposition', $disposition->id)
            ->call('createForcedTimers')
            ->assertSet('selected', [])
            ->assertSet('selectedDisposition', null);

        $this->assertDatabaseHas('timy_timers', ['user_id' => $user->id, 'finished_at' => null]);
    }

    /** @test */
    public function it_listen_for_events()
    {
        $users = $this->usersWithTimers();;

        $livewire = Livewire::test('timy::forced-timer-management')
            ->assertSet('users', $users);

        $disposition = Disposition::factory()->create();
        $this->user()->startTimer($disposition->id, ['forced' => true]);

        $livewire
            ->emit('timyRoleUpdated')
            ->assertSet('users', $users)
            ->emit('echo-private:Timy.Admin,\\Dainsys\\Timy\\Events\\TimerCreatedAdmin')
            ->assertSet('users', $users);
    }

    protected function usersWithTimers(int $amount = 1)
    {
        return User::orderBy('name')
            ->with(['timers' => function ($query) {
                $query->running()
                    ->with('disposition');
            }])
            ->isTimyUser()
            ->get();
    }
}
