<?php

namespace Dainsys\Timy\Tests\Unit;

use App\User;
use Dainsys\Timy\Http\Livewire\TeamsTable;
use Dainsys\Timy\Repositories\DispositionsRepository;
use Dainsys\Timy\Models\Team;
use Dainsys\Timy\Repositories\TeamsRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

trait TeamsTestsTrait
{
    /** @test */
    public function teams_component_fetch_teams()
    {
        $this->actingAs($this->user(['email' => config('timy.super_admin_email')]));

        factory(Team::class, 5)->create();

        $this->get(route('super_admin_dashboard'));

        Livewire::test(TeamsTable::class)
            ->assertSee(__('timy::titles.create_teams_form_header'))
            ->assertSee(__('timy::titles.teams_header'))
            ->assertSee(__('timy::titles.without_teams_header'))
            ->assertSet('name', null)
            ->assertSet('selected', [])
            ->assertSet('selectedTeam', null)
            ->assertSet('teams', TeamsRepository::all())
            ->assertSet('users_without_team', User::withoutTeam()->orderBy('name')->get());
    }
    /** @test */
    public function teams_component_validates_before_creating_a_team()
    {
        $this->actingAs($this->user(['email' => config('timy.super_admin_email')]));

        $team = factory(Team::class)->create();

        Livewire::test(TeamsTable::class)
            ->set('name', '')
            ->call('createTeam')
            ->assertHasErrors('name', 'required')
            ->set('name', $team->name)
            ->call('createTeam')
            ->assertHasErrors('name', 'unique')
            ->set('name', 'aa')
            ->call('createTeam')
            ->assertHasErrors('name', 'min');
    }
    /** @test */
    public function teams_component_create_a_team()
    {
        $this->actingAs($this->user(['email' => config('timy.super_admin_email')]));

        Livewire::test(TeamsTable::class)
            ->set('name', 'New Team')
            ->call('createTeam')
            ->assertSet('name', '');

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
}
