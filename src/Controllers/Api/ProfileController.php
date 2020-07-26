<?php

namespace Dainsys\Timy\Controllers;

use App\User;
use Carbon\Carbon;
use Dainsys\Timy\Disposition;
use Dainsys\Timy\Rules\DateRangeInDays;
use Dainsys\Timy\Exports\HoursExport;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class ProfileController extends BaseController
{
    public function index()
    {
        $users = User::orderBy('name')
            ->get();
    }

    public function show(User $user)
    {
        return $user->ti
    }
}
