<?php

namespace Dainsys\Timy\Controllers\Api;

use Dainsys\Timy\Role;
use Illuminate\Support\Facades\Gate;

class SuperAdminController extends BaseController
{
    public function index()
    {
        if (Gate::denies(config('timy.super_admin.role'))) {
            abort(403);
        }

        return response()->json([
            'data' => [
                'roles' => Role::with('users')->get(),
                'unassigned' => resolve('TimyUser')->whereDoesntHave('timy_role')->get()
            ]
        ]);
    }

    public function assign($user, Role $role)
    {
        if (Gate::denies(config('timy.super_admin.role'))) {
            return abort(403);
        }

        $user = resolve('TimyUser')->findOrFail($user);

        $user->assigTimyRole($role);

        return response()->json([
            'data' => $user->load('timy_role')
        ]);
    }

    public function unAssign($user) 
    {
        if (Gate::denies(config('timy.super_admin.role'))) {
            return abort(403);
        }

        $user = resolve('TimyUser')->findOrFail($user);

        $user->removeTimyRole();

        return response()->json([
            'data' => $user->load('timy_role')
        ]);
    }
}
