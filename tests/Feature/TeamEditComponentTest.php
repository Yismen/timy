<?php

namespace Dainsys\Timy\Tests\Feature;

use Dainsys\Timy\Http\Livewire\TeamEditComponent;
use Dainsys\Timy\Models\Team;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class TeamEditComponentTest extends TestCase
{
    /** @test */
    public function teams_component_inits_and_fetch_teams()
    {
        $this->actingAs($this->user(['email' => config('timy.super_admin_email')]));

        $this->get(route('super_admin_dashboard'));

        Livewire::test(TeamEditComponent::class)
            ->assertSet('team', new Team())
            ->assertViewIs('timy::livewire.team.edit-component');
    }

    /** @test */
    public function it_listen_and_responds_to_events()
    {
        $team = Team::factory()->create();

        Livewire::test(TeamEditComponent::class)
            ->emit('wantsEditTeam', $team->id)
            ->assertSet("team", $team->fresh())
            ->assertDispatchedBrowserEvent('show-edit-team-modal')
            ->emit('wantsDeleteTeam', $team->id)
            ->assertSet("team", $team->fresh())
            ->assertDispatchedBrowserEvent('show-delete-team-modal');
    }

    /** @test */
    public function it_validates_name_before_updating_a_team()
    {
        $team = Team::factory()->create();

        Livewire::test(TeamEditComponent::class)
            ->call('editTeam', $team->id)
            ->set("team.name", '')
            ->call('updateTeam')
            ->assertHasErrors(['team.name' => 'required'])
            ->set("team.name", 'a')
            ->call('updateTeam')
            ->assertHasErrors(['team.name' => 'min']);
    }

    /** @test */
    public function team_name_can_be_updated()
    {
        $team = Team::factory()->create();

        Livewire::test(TeamEditComponent::class)
            ->call('editTeam', $team->id)
            ->set('team.name', "Updated Name")
            ->call('updateTeam')
            ->assertDispatchedBrowserEvent('hide-edit-team-modal')
            ->assertEmitted('teamUpdated')
            ->assertSet('team', new Team());

        $this->assertDatabaseMissing('timy_teams', ['name' => $team->name]);
        $this->assertDatabaseHas('timy_teams', ['name' => 'Updated Name']);
    }

    /** @test */
    public function it_prompts_to_delete_team()
    {
        $team = Team::factory()->create();

        Livewire::test(TeamEditComponent::class)
            ->call('beforeRemovingTeam', $team->id)
            ->assertSet('team', $team->fresh())
            ->assertDispatchedBrowserEvent('show-delete-team-modal');

        $this->assertDatabaseHas('timy_teams', ['name' => $team->name]);
    }

    /** @test */
    public function it_release_users_before_removing_a_team()
    {
        $team = Team::factory()->create();
        $user = $this->user();
        $user->assignTimyTeam($team);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'timy_team_id' => $team->id]);

        Livewire::test(TeamEditComponent::class)
            ->call('beforeRemovingTeam', $team->id)
            ->call('removeTeam');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'timy_team_id' => null]);

        $this->assertDatabaseMissing('timy_teams', ['name' => $team->name]);
    }
    /** @test */
    public function it_deletes_a_team()
    {
        $team = Team::factory()->create();

        Livewire::test(TeamEditComponent::class)
            ->call('beforeRemovingTeam', $team->id)
            ->call('removeTeam')
            ->assertSet('team', new Team())
            ->assertEmitted('teamUpdated')
            ->assertDispatchedBrowserEvent('hide-delete-team-modal');

        $this->assertDatabaseMissing('timy_teams', ['name' => $team->name]);
    }
}
