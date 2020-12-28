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
            ->assertSee(__('timy::titles.create_teams_form_header'))
            ->assertSee(__('timy::titles.teams_header'))
            ->assertSet('team', new Team())
            ->assertSet('team.name', null)
            ->assertSet('selected', [])
            ->assertSet('selectedTeam', null)
            ->assertSet('teams', TeamsRepository::all())
            // ->assertSee(__('timy::titles.without_teams_header'))
            ->assertSet('users_without_team', User::withoutTeam()->whereHas('timy_role')->orderBy('name')->get());
    }
    /** @test */
    public function teams_component_validates_before_creating_a_team()
    {
        $this->actingAs($this->user(['email' => config('timy.super_admin_email')]));

        $team = factory(Team::class)->create();

        Livewire::test(TeamsTable::class)
            ->set('team.name', '')
            ->call('createTeam')
            ->assertHasErrors('team.name', 'required')
            ->set('team.name', $team->name)
            ->call('createTeam')
            ->assertHasErrors('team.name', 'unique')
            ->set('team.name', 'aa')
            ->call('createTeam')
            ->assertHasErrors('team.name', 'min');
    }
    /** @test */
    public function teams_component_create_a_team()
    {
        $this->actingAs($this->user(['email' => config('timy.super_admin_email')]));

        Livewire::test(TeamsTable::class)
            ->set('team.name', 'New Team')
            ->call('createTeam')
            ->assertSet('team', new Team());

        $this->assertDatabaseHas('timy_teams', ['name' => 'New Team']);
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
    public function teams_component_listen_for_events()
    {
        $livewire = Livewire::test(TeamsTable::class);

        factory(Team::class)->create();

        $livewire
            ->emit('timyRoleUpdated')
            ->assertSet('teams', TeamsRepository::all())
            ->assertSet('users_without_team', TeamsRepository::usersWithoutTeam());
    }

    /** @test */
    public function it_sets_the_team_var_and_emit_event_to_show_edit_form()
    {
        $team = factory(Team::class)->create();

        Livewire::test(TeamsTable::class)
            ->assertSet('team', new Team())
            ->call('editTeam', $team->id)
            ->assertSet("team", $team->fresh())
            ->assertDispatchedBrowserEvent('show-edit-team-modal');
    }

    /** @test */
    public function a_name_is_required_to_edit_at_team()
    {
        $team = factory(Team::class)->create();

        Livewire::test(TeamsTable::class)
            ->call('editTeam', $team->id)
            ->set("team.name", '')
            ->call('updateTeam', $team->id)
            ->assertHasErrors(['team.name']);
    }

    /** @test */
    public function team_name_can_be_updated()
    {
        $team = factory(Team::class)->create();

        Livewire::test(TeamsTable::class)
            ->call('editTeam', $team->id)
            ->set('team.name', "Updated Name")
            ->call('updateTeam', $team->id)
            ->assertDispatchedBrowserEvent('hide-edit-team-modal')
            ->assertSet('team', new Team());

        $this->assertDatabaseMissing('timy_teams', ['name' => $team->name]);
        $this->assertDatabaseHas('timy_teams', ['name' => 'Updated Name']);
    }

    /** @test */
    public function it_prompts_to_delete_team()
    {
        $team = factory(Team::class)->create();

        Livewire::test(TeamsTable::class)
            ->call('beforeRemovingTeam', $team->id)
            ->assertSet('team', $team->fresh())
            ->assertDispatchedBrowserEvent('show-delete-team-modal');

        $this->assertDatabaseHas('timy_teams', ['name' => $team->name]);
    }

    /** @test */
    public function it_release_users_before_removing_a_team()
    {
        $team = factory(Team::class)->create();
        $user = factory(User::class)->create();
        $user->assignTimyTeam($team);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'timy_team_id' => $team->id]);

        Livewire::test(TeamsTable::class)
            ->call('beforeRemovingTeam', $team->id)
            ->call('removeTeam');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'timy_team_id' => null]);

        $this->assertDatabaseMissing('timy_teams', ['name' => $team->name]);
    }
    /** @test */
    public function it_deletes_a_team()
    {
        $team = factory(Team::class)->create();

        Livewire::test(TeamsTable::class)
            ->call('beforeRemovingTeam', $team->id)
            ->call('removeTeam')
            ->assertSet('team', new Team())
            ->assertDispatchedBrowserEvent('hide-delete-team-modal');

        $this->assertDatabaseMissing('timy_teams', ['name' => $team->name]);
    }
}
