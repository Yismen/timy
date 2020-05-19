<?php

namespace Dainsys\Timy\Controllers;

use Dainsys\Timy\Models\Timer;

class TimerController extends BaseController
{
    public function index()
    {
        return Timer::latest()->paginate(50);
    }

    public function show(Timer $timy_timer)
    {
        return response()->json(['data' => $timy_timer]);
    }

    protected function store()
    {
        $this->validate(request(), [
            'task_id' => 'exists:timy_tasks,id'
        ]);

        $task = auth()->user()->timers()->create(request()->all());

        return response()->json(['data' => $task]);
    }

    public function update(Timer $timy_timer)
    {
        $this->validate(request(), [
            'task_id' => 'exists:timy_tasks,id'
        ]);

        $timy_timer->update(request()->all());

        return response()->json(['data' => $timy_timer]);
    }
}
