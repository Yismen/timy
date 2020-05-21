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
            'disposition_id' => 'exists:timy_dispositions,id'
        ]);

        $disposition = auth()->user()->timers()->create(
            array_merge(request()->all(), ['started_at' => now()])
        );

        return response()->json(['data' => $disposition]);
    }

    public function update(Timer $timy_timer)
    {
        $this->validate(request(), [
            'disposition_id' => 'exists:timy_dispositions,id'
        ]);

        $timy_timer->update(request()->all());

        return response()->json(['data' => $timy_timer]);
    }

    protected function closeAll()
    {
        auth()->user()->timers()->running()->each(function ($timer) {
            $timer->stop();
        });

        return response()->json(['data' => auth()->user()->timers()->running()->get()]);
    }

    public function running()
    {
        return response()->json([
            'data' => auth()->user()->timers()->running()->first()
        ]);
    }
}
