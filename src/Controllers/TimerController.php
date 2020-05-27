<?php

namespace Dainsys\Timy\Controllers;

use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Models\Timer;

class TimerController extends BaseController
{
    public function index()
    {
        return TimerResource::collection(
            Timer::latest()->with(['disposition', 'user'])->paginate(20)
        );
    }

    public function show(Timer $timer)
    {
        return response()->json(['data' => $timer]);
    }

    protected function store()
    {
        $this->validate(request(), [
            'disposition_id' => 'exists:timy_dispositions,id'
        ]);

        $timer = auth()->user()->timers()->create(
            array_merge(request()->all(), ['started_at' => now()])
        );

        TimerResource::withoutWrapping();

        return response()->json(['data' => new TimerResource($timer)]);
    }

    public function update(Timer $timer)
    {
        $this->validate(request(), [
            'disposition_id' => 'exists:timy_dispositions,id'
        ]);

        $timer->update(request()->all());

        TimerResource::withoutWrapping();

        return response()->json(['data' => new TimerResource($timer)]);
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

    public function ping()
    {
        return response()->json([
            'Session is alive!'
        ]);
    }
}
