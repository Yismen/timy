<?php

namespace Dainsys\Timy\Tests\Unit;

use App\User;
use Carbon\Carbon;
use Dainsys\Timy\Http\Livewire\OpenTimersMonitor;
use Dainsys\Timy\Models\Disposition;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Repositories\TimersRepository;
use Dainsys\Timy\Resources\TimerResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

trait OpenTimersMonitorTestsTrait
{
    /** @test */
    public function it_renders_opent_timers_component()
    {
        $this->actingAs($this->adminUser());
        $disposition = factory(Disposition::class)->create();

        $users = factory(User::class, 5)->create()
            ->each->startTimer($disposition->id, ['forced' => true]);

        Livewire::test(OpenTimersMonitor::class)
            ->assertSee(__('timy::titles.open_timers_header'))
            ->assertSet('selectedDisposition', null)
            ->assertSet('dispositions', Cache::remember('timy_dispositions', now()->addMinutes(60), function () {
                return Disposition::orderBy('name')->get();
            }))
            ->assertSet('usersWithoutTimers', [])
            ->assertSet('all', false)
            ->assertSet('timers', [])
            ->assertViewIs('timy::livewire.open-timers-monitor');
    }
    /** @test */
    public function it_fetches_open_timers()
    {
        $this->actingAs($this->adminUser());

        Livewire::test(OpenTimersMonitor::class)
            ->call('getOpenTimers')
            ->assertSet('timers', TimerResource::collection(
                TimersRepository::all()
            )->jsonSerialize());
    }
    /** @test */
    public function open_timers_component_toggles_selection()
    {
        $this->actingAs($this->adminUser());

        $user = $this->user();

        Livewire::test(OpenTimersMonitor::class)
            ->call('toggleSelected', $user->id)
            ->assertSet('selected', [$user->id])
            ->call('toggleSelected', $user->id)
            ->assertSet('selected', []);
    }
    /** @test */
    public function open_timers_resets_selection()
    {
        $this->actingAs($this->adminUser());

        $user = $this->user();

        Livewire::test(OpenTimersMonitor::class)
            ->call('toggleSelected', $user->id)
            ->call('resetSelectors')
            ->assertSet('selected', [])
            ->assertSet('selectedDisposition', null)
            ->assertSet('all', false);
    }
    /** @test */
    public function open_timers_validates_before_updating_timers()
    {
        $this->actingAs($this->adminUser());

        Livewire::test(OpenTimersMonitor::class)
            ->set('selected', [])
            ->set('selectedDisposition', null)
            ->call('updateSelectedTimers')
            ->assertHasErrors(['selectedDisposition', 'selected'], 'required')
            ->set('selected', null)
            ->assertHasErrors(['selected'], 'array');
    }

    /** @test */
    public function open_timers_updates_selected_timers()
    {
        $user = $this->adminUser();
        $this->actingAs($user);
        $now = now()->startOfWeek(2)->setHour('10'); // Tuesday, 10:00 am
        Carbon::setTestNow($now);
        $this->actingAs($this->adminUser());
        $users = factory(User::class, 4)->create();
        $disposition = factory(Disposition::class)->create();

        Livewire::test(OpenTimersMonitor::class)
            ->set('selected', $users->pluck('id'))
            ->set('selectedDisposition', $disposition->id)
            ->call('updateSelectedTimers')
            ->assertSet('selected', [])
            ->assertSet('selectedDisposition', null)
            ->assertSet('all', false);

        $this->assertCount(4, Timer::where('disposition_id', $disposition->id)->get());
    }

    /** @test */
    public function open_timers_closes_selected_timers()
    {
        $this->actingAs($this->adminUser());
        $disposition = factory(Disposition::class)->create();

        $users = factory(User::class, 4)->create()
            ->each->startTimer($disposition->id, ['forced' => true]);

        $disposition = factory(Disposition::class)->create();

        Livewire::test(OpenTimersMonitor::class)
            ->set('selected', $users->pluck('id'))
            ->call('closeSelectedTimers')
            ->assertSet('selected', [])
            ->assertSet('selectedDisposition', null)
            ->assertSet('all', false);

        $this->assertCount(0, Timer::where('finished_at', null)->get());
    }

    /** @test */
    // public function open_timers_component_assign_open_timers()
    // {
    //     $this->actingAs($this->adminUser());

    //     $user = $this->user();
    //     $open_timer = factory(Team::class)->create();

    //     Livewire::test(OpenTimersMonitor::class)
    //         ->set('selectedTeam', $open_timer->id)
    //         ->call('toggleSelected', $user->id)
    //         ->call('assignTeam')
    //         ->assertSet('selected', []);

    //     $this->assertDatabaseHas('users', ['timy_open_timer_id' => $open_timer->id]);
    // }
}
