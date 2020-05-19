<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TimyTaskTests extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_tasks()
    {
        factory(Task::class, 10)->create();

        $this->get('/timy_tasks')
            ->assertOk()
            ->assertJson([
                'data' => [],
                'to' => 10,
                'per_page' => 50
            ]);
    }

    /** @test */
    public function user_can_see_a_single_tasks()
    {
        $task = factory(Task::class)->create();

        $this->get(route('timy_tasks.show', $task->id))
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

        $this->post(route('timy_tasks.store'), $task)
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

        $this->put(route('timy_tasks.update', $task->id), [
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
        $this->post(route('timy_tasks.store'), ['name' => null])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function name_is_required_to_update_a_task()
    {
        $task = factory(Task::class)->create();

        $this->put(route('timy_tasks.update', $task->id), ['name' => null])
            ->assertSessionHasErrors(['name']);
    }
}
