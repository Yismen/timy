<?php

namespace Dainsys\Timy\Controllers;

use Dainsys\Timy\Models\Task;

class TaskController extends BaseController
{
    public function index()
    {
        return response()->json([
            'data' => Task::orderBy('name')->get(),
            'status' => 200
        ]);
    }

    public function show(Task $timy_task)
    {
        return response()->json(['data' => $timy_task]);
    }

    protected function store()
    {
        $this->validate(request(), [
            'name' => 'required'
        ]);

        $task = Task::create(request()->all());

        return response()->json(['data' => $task]);
    }

    public function update(Task $timy_task)
    {
        $this->validate(request(), [
            'name' => 'required'
        ]);

        $timy_task->update(request()->all());

        return response()->json(['data' => $timy_task]);
    }
}
