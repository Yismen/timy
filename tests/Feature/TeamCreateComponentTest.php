<?php

namespace Dainsys\Timy\Tests\Feature;

use Dainsys\Timy\Http\Livewire\TeamCreateComponent;
use Dainsys\Timy\Models\Team;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class TeamCreateComponentTest extends TestCase
{
    /** @test */
    public function team_create_component_inits()
    {
        $this->actingAs($this->user(['email' => config('timy.super_admin_email')]));

        $this->get(route('super_admin_dashboard'));

        Livewire::test(TeamCreateComponent::class)
            ->assertViewIs('timy::livewire.team.create-component')
            ->assertSee(__('timy::titles.create_teams_form_header'));
    }
    /** @test */
    public function teams_component_validates_before_creating_a_team()
    {
        $this->actingAs($this->user(['email' => config('timy.super_admin_email')]));

        $team = Team::factory()->create();

        Livewire::test(TeamCreateComponent::class)
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

        Livewire::test(TeamCreateComponent::class)
            ->set('team.name', 'New Team')
            ->call('createTeam')
            ->assertSet('team', new Team())
            ->assertEmitted('teamCreated');

        $this->assertDatabaseHas('timy_teams', ['name' => 'New Team']);
    }
}
