<?php

namespace Dainsys\Timy\Http\Controllers\Api;

use Dainsys\Timy\Models\Disposition;
use Dainsys\Timy\Events\TimerCreated;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Repositories\TimersRepository;
use Illuminate\Support\Facades\Gate;

class AdminController extends BaseController
{
    public function index()
    {
        if (Gate::denies(config('timy.roles.admin'))) {
            abort(403);
        }
        
        TimerResource::withoutWrapping();

        return response()->json([
            'data' => [
                'dispositions' => Disposition::orderBy('name')->get(),
                'running_timers' => TimerResource::collection(TimersRepository::all()),
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
