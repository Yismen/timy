<?php

namespace Dainsys\Timy\Http\Controllers;

use Dainsys\Timy\Events\TimerCreatedAdmin;

class TimyUserController extends BaseController
{
    /**
     * Ping to check if user session is still alive.
     *
     * @return void
     */
    public function ping()
    {
        if (!auth()->check()) {
            return redirect()->route(route('login'));
        }

        return response()->json([
            'Authenticated!',
            'timer' => auth()->user()->timers()->running()->first(),
        ], 200);
    }
    /**
     * Respond to the browser closed event.
     *
     * @return void
     */
    protected function userDisconnected()
    {
        $user = auth()->user();

        $user->stopRunningTimers();

        event(new TimerCreatedAdmin($user));
    }
}
