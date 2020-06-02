<?php

namespace Dainsys\Timy\Controllers\Api;

use Dainsys\Timy\Disposition;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Timer;
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
                'dispositions' => Disposition::get(),
                'running_timers' => TimerResource::collection( $timers),
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
        
        return response()->json([
            'data' => TimerResource::make($timer)
        ]);
    }
}
