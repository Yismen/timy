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
        $users = User::orderBy('name')
            ->with(['timers' => function ($query) {
                $query->running()
                    ->with('disposition');
            }])
            ->isTimyUser()
            ->get();

        Livewire::test('timy::forced-timer-management')
            ->assertViewIs('timy::livewire.forced-timer-management')
            ->assertViewHas('users', null)
            ->assertViewHas('dispositions', Disposition::orderBy('name')->get());
    }

    /** @test */
    public function forced_timers_view_has_key_words()
    {
        Livewire::test(ForcedTimerManagement::class)
            ->assertSee('getUsers')
            ->set('selected', [1])
            ->assertSee('closeForm')
            ->assertSee('createForcedTimers');
    }

    /** @test */
    public function it_toogles_a_user_when_selected()
    {
        $user = $this->user();

        Livewire::test('timy::forced-timer-management')
            ->assertSet('selected', [])
            ->call('toggleSelection', $user->id)
            ->assertSet('selected', [$user->id])
            ->call('toggleSelection', $user->id)
            ->assertSet('selected', [])
            ->assertSet('selectedDisposition', null);
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
        $disposition = factory(Disposition::class)->create();

        Livewire::test('timy::forced-timer-management')
            ->call('toggleSelection', $user->id)
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
        $livewire = Livewire::test('timy::forced-timer-management')
            ->assertSet('users', null);

        $disposition = factory(Disposition::class)->create();
        $this->user()->startTimer($disposition->id, ['forced' => true]);

        $livewire
            ->emit('timyRoleUpdated')
            ->assertSet('users', $this->usersWithTimers())
            ->emit('echo-private:Timy.Admin,\\Dainsys\\Timy\\Events\\TimerCreatedAdmin')
            ->assertSet('users', $this->usersWithTimers());
    }

    protected function usersWithTimers()
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
