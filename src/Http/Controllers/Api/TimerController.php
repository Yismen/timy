<?php

namespace Dainsys\Timy\Http\Controllers\Api;

use Dainsys\Timy\Events\TimerCreatedAdmin;
use Dainsys\Timy\Resources\TimerResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

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

    public function getOpenTimersHours(): JsonResponse
    {
        $timers = $this->user->timers()->running()->get()
            ->map(function ($timer) {
                $timer->finished_at = now();
                return TimerResource::make($timer);
            });

        return response()->json([
            'hours' => (float) $timers->sum('payable_hours')
        ]);
    }

    protected function userDisconnected()
    {
        $this->user->stopRunningTimers();

        event(new TimerCreatedAdmin($this->user));
    }

    public function ping()
    {
        return response()->json([
            'Authenticated!'
        ]);
    }
}
