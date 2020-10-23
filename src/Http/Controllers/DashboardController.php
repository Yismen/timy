<?php

namespace Dainsys\Timy\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Dainsys\Timy\Disposition;
use Dainsys\Timy\Rules\DateRangeInDays;
use Dainsys\Timy\Exports\HoursExport;
use Dainsys\Timy\Repositories\UserDataRepository;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends BaseController
{
    protected $fileName;

    public function __construct()
    {
        $this->fileName  = 'timy_hours_'
            . Carbon::parse(request('date_from'))->format('Ymd')
            . '_'
            . Carbon::parse(request('date_to'))->format('Ymd')
            . '.xls';
    }

    public function user()
    {
        if (Gate::denies(config('timy.roles.user'))) {
            abort(403, 'Unauthorized');
        }

        return view('timy::dashboards.user', UserDataRepository::toArray(auth()->user()));
    }

    public function superAdmin()
    {
        if (Gate::denies(config('timy.roles.super_admin'))) {
            abort(403, 'Unauthorized');
        }

        $dispositions = Disposition::orderBy('name')->get();

        return view('timy::dashboards.super-admin', compact('dispositions'));
    }
    public function admin()
    {
        if (Gate::denies(config('timy.roles.admin'))) {
            abort(403, 'Unauthorized');
        }

        return view('timy::dashboards.admin', [
            'users' => User::orderBy('name')
                ->whereHas('timy_role', function ($query) {
                    $query->where('name', config('timy.roles.user'))
                        ->orWhere('name', config('timy.roles.admin'));
                })
                ->get()
                ->split(2),
        ]);
    }

    public function hours()
    {
        if (Gate::denies(config('timy.roles.admin'))) {
            abort(403, 'Unauthorized');
        }

        $this->validateRequest();

        return Excel::download(
            new HoursExport(
                request('date_from'),
                request('date_to')
            ),
            $this->fileName
        );
    }

    public function profile(User $user)
    {
        if (Gate::denies(config('timy.roles.admin'))) {
            abort(403, 'Unauthorized');
        }

        return  view('timy::user-profile', [
            'user' => $user,
            'data' => UserDataRepository::toArray($user)
        ]);
    }

    public function validateRequest()
    {
        return $this->validate(request(), [
            'date_from' => 'required|date',
            'date_to' => [
                'required',
                'date',
                new DateRangeInDays(31)
            ]
        ]);
    }
}
