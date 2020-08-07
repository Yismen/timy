<?php

namespace Dainsys\Timy\Controllers\Api;

use App\User;
use Dainsys\Timy\Disposition;
use Dainsys\Timy\Events\TimerCreated;
use Dainsys\Timy\Events\TimerCreatedAdmin;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Role;
use Illuminate\Support\Facades\Gate;

class SuperAdminController extends BaseController
{
    public function index()
    {
        if (Gate::denies(config('timy.roles.super_admin'))) {
            abort(403);
        }

        return response()->json([
            'data' => [
                'roles' => Role::with('users')->get(),
                'unassigned' => resolve('TimyUser')->whereDoesntHave('timy_role')->get(),
                'dispositions' => Disposition::orderBy('name')->get(),
                'users' => User::orderBy('name')
                    ->whereHas('timy_role')->get(),
            ]
        ]);
    }

    public function assign($user, Role $role)
    {
        if (Gate::denies(config('timy.roles.super_admin'))) {
            return abort(403);
        }

        $user = resolve('TimyUser')->findOrFail($user);
        $user->assignTimyRole($role);

        return response()->json([
            'data' => $user->load('timy_role')
        ]);
    }

    public function unAssign($user)
    {
        if (Gate::denies(config('timy.roles.super_admin'))) {
            return abort(403);
        }

        $user = resolve('TimyUser')->findOrFail($user);

        $user->removeTimyRole();

        return response()->json([
            'data' => $user->load('timy_role')
        ]);
    }

    public function createForcedTimer($user, Disposition $disposition)
    {
        if (Gate::denies(config('timy.roles.super_admin'))) {
            abort(403);
        }

        $this->validate(request(), [
            'user' => 'exists:users,id',
        ]);

        $user = resolve('TimyUser')->findOrFail($user);

        $user->stopRunningTimers();

        $timer = $user->startTimer($disposition->id, ['forced' => true]);

        event(new TimerCreated($user, $timer));
        event(new TimerCreatedAdmin($user, $timer));

        return response()->json([
            'data' => TimerResource::make($timer)
        ]);
    }
}
