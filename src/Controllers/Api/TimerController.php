<?php

namespace Dainsys\Timy\Controllers\Api;

use App\User;
use Dainsys\Timy\Events\TimerCreated;
use Dainsys\Timy\Events\TimerCreatedAdmin;
use Dainsys\Timy\Events\TimerStopped;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Timer;
use Illuminate\Support\Facades\Auth;

class TimerController extends BaseController
{
    private $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
    }

    public function index()
    {
        return TimerResource::collection(
            Timer::orderBy('started_at', 'desc')
                ->with(['disposition', 'user'])
                ->mine()
                ->paginate(20)
        );
    }

    // public function show(Timer $timer)
    // {
    //     return response()->json(['data' => $timer]);
    // }

    protected function store()
    {
        $this->validate(request(), [
            'disposition_id' => 'exists:timy_dispositions,id'
        ]);
        try {
            $this->user->stopRunningTimers();
            $timer = $this->user->startTimer(request('disposition_id'));

            event(new TimerCreated($this->user, $timer));
            event(new TimerCreatedAdmin($this->user, $timer));

            TimerResource::withoutWrapping();

            return response()->json(['data' => new TimerResource($timer)], 200);
        } catch (\Throwable $th) {
            $code = (int) $th->getCode();
            return response()->json([
                'user' => $this->user,
                'message' => $th->getMessage(),
                'exception' => get_class($th)
            ], $code > 0 ? $code : 500);
        }
    }

    public function destroy(Timer $timer)
    {
        $timer->stop();
        $user = User::find($timer->user_id);

        event(new TimerStopped($user, $timer));

        TimerResource::withoutWrapping();

        return response()->json(['data' => TimerResource::make($timer)], 200);
    }

    protected function closeAll()
    {
        $this->user->stopRunningTimers();

        return response()->json([
            'data' => $this->user->timers()->running()->get()
        ]);
    }

    public function running()
    {
        return response()->json([
            'data' => $this->user->timers()->running()->first()
        ]);
    }

    public function ping()
    {
        return response()->json([
            'Authenticated!'
        ]);
    }
}
