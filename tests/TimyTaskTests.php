<?php

namespace Dainsys\Timy\Tests;

use App\User;
use Dainsys\Timy\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class TimyTaskTests extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_tasks()
    {
        $tasks = factory(Task::class, 10)->create();
        $tasks = $tasks->sortBy('name');

        $this->get(route('timy_tasks.index', ['api_token' => $this->user->api_token]))
            ->assertOk()
            ->assertJson([
                'data' => []
            ])
            ->assertJsonCount(10, 'data');
    }

    /** @test */
    public function user_can_see_a_single_tasks()
    {
        $task = factory(Task::class)->create();

        $this->get(route('timy_tasks.show', ['timy_task' => $task->id, 'api_token' => $this->user->api_token]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $task->id,
                    'name' => $task->name,
                    'invoiceable' => $task->invoiceable,
                ]
            ]);
    }

    /** @test */
    public function a_task_can_be_created()
    {
        $task = factory(Task::class)->make()->toArray();

        $this->post(route('timy_tasks.store', ['api_token' => $this->user->api_token]), $task)
            ->assertOk()
            ->assertJson([
                'data' => $task
            ]);

        $this->assertDatabaseHas('timy_tasks', $task);
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $task = factory(Task::class)->create();

        $this->put(route('timy_tasks.update', ['timy_task' => $task->id, 'api_token' => $this->user->api_token]), [
            'name' => 'Updated Name'
        ])
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $task->id,
                    'name' => 'Updated Name',
                    'invoiceable' => $task->invoiceable,
                ]
            ]);

        $this->assertDatabaseHas('timy_tasks', [
            'name' => 'Updated Name'
        ]);
    }

    /** @test */
    public function name_is_required_to_create_a_task()
    {
        $this->post(route('timy_tasks.store', ['api_token' => $this->user->api_token]), ['name' => null])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function name_is_required_to_update_a_task()
    {
        $task = factory(Task::class)->create();

        $this->put(route('timy_tasks.update', ['timy_task' => $task->id, 'api_token' => $this->user->api_token]), ['name' => null])
            ->assertSessionHasErrors(['name']);
    }
}
