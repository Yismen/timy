<?php

namespace Dainsys\Timy\Controllers;

use Illuminate\Support\Facades\Gate;

class DashboardController extends BaseController
{
    public function user()
    {
        return view('timy::user-dashboard');
    }
    public function superAdmin()
    {
        // if (Gate::denies(config('timy.super_admin.role'))) {
        //     abort(403, 'Unauthorized');
        // }

        return view('timy::super-admin-dashboard');
    }
}
