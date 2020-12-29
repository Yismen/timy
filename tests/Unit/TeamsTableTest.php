<?php

namespace Dainsys\Timy\Tests\Unit;

use App\User;
use Dainsys\Timy\Http\Livewire\TeamsTable;
use Dainsys\Timy\Models\Team;
use Dainsys\Timy\Repositories\TeamsRepository;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class TeamsTableTest extends TestCase
{
    /** @test */
    public function teams_component_inits_and_fetch_teams()
    {
        $this->actingAs($this->user(['email' => config('timy.super_admin_email')]));

        factory(Team::class, 5)->create();

        $this->get(route('super_admin_dashboard'));

        Livewire::test(TeamsTable::class)
            ->assertViewIs('timy::livewire.teams-table')
            ->assertSee(__('timy::titles.teams_header'))
            ->assertSet('selected', [])
            ->assertSet('selectedTeam', null)
            ->assertSet('teams', TeamsRepository::all())
            ->assertSet('users_without_team', User::withoutTeam()->whereHas('timy_role')->orderBy('name')->get());
    }

    /** @test */
    public function teams_component_toggles_selection()
    {
        $this->actingAs($this->user(['email' => config('timy.super_admin_email')]));

        $user = $this->user();

        Livewire::test(TeamsTable::class)
            ->call('toggleSelection', $user->id)
            ->assertSet('selected', [$user->id])
            ->call('toggleSelection', $user->id)
            ->assertSet('selected', []);
    }
    /** @test */
    public function teams_component_assign_teams()
    {
        $this->actingAs($this->user(['email' => config('timy.super_admin_email')]));

        $user = $this->user();
        $team = factory(Team::class)->create();

        Livewire::test(TeamsTable::class)
            ->set('selectedTeam', $team->id)
            ->call('toggleSelection', $user->id)
            ->call('assignTeam')
            ->assertSet('selected', []);

        $this->assertDatabaseHas('users', ['timy_team_id' => $team->id]);
    }

    /** @test */
    public function teams_component_responds_to_timy_role_updated_event()
    {
        $livewire = Livewire::test(TeamsTable::class);

        $teams = factory(Team::class, 3)->create();
        // Responds to User role updated
        $livewire
            ->emit('timyRoleUpdated')
            ->assertSet('teams', TeamsRepository::all())
            ->assertSet('users_without_team', TeamsRepository::usersWithoutTeam());
    }

    /** @test */
    public function teams_component_responds_to_team_updated_event()
    {
        $livewire = Livewire::test(TeamsTable::class);
        $teams = factory(Team::class, 3)->create();

        $teams->first()->update(['name' => 'updated name']);
        $livewire
            ->emit('teamUpdated')
            ->assertSet('teams', TeamsRepository::all());
    }

    /** @test */
    public function teams_component_responds_to_team_deleted_event()
    {
        $livewire = Livewire::test(TeamsTable::class);
        $teams = factory(Team::class, 3)->create();

        $teams->first()->delete();
        $livewire
            ->emit('teamUpdated')
            ->assertSet('teams', TeamsRepository::all());
    }

    /** @test */
    public function teams_component_emits_wants_to_update_event()
    {
        $team = factory(Team::class)->create();
        Livewire::test(TeamsTable::class)
            ->call('editTeam', $team->id)
            ->assertEmitted('wantsEditTeam', $team->id);
    }

    /** @test */
    public function teams_component_emits_wants_delete_event()
    {
        $team = factory(Team::class)->create();
        Livewire::test(TeamsTable::class)
            ->call('removeTeam', $team->id)
            ->assertEmitted('wantsDeleteTeam', $team->id);
    }
}
