<?php

namespace Dainsys\Timy\Http\Controllers\Api;

use Dainsys\Timy\Models\Disposition;
use Dainsys\Timy\Events\TimerCreated;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Models\Timer;
use Illuminate\Support\Facades\Gate;

class AdminController extends BaseController
{
    public function index()
    {
        if (Gate::denies(config('timy.roles.admin'))) {
            abort(403);
        }

        $timers = Timer::with(['user', 'disposition'])->running()->orderBy('disposition_id')
            ->get();
        TimerResource::withoutWrapping();

        return response()->json([
            'data' => [
                'dispositions' => Disposition::orderBy('name')->get(),
                'running_timers' => TimerResource::collection($timers),
            ]
        ]);
    }

    public function store($user, Disposition $disposition)
    {
        if (Gate::denies(config('timy.roles.admin'))) {
            abort(403);
        }

        $this->validate(request(), [
            'user' => 'exists:users,id',
        ]);

        $user = resolve('TimyUser')->findOrFail($user);

        $timer = $user->startTimer($disposition->id);

        event(new TimerCreated($user, $timer));

        return response()->json([
            'data' => $timer
        ]);
    }
}
