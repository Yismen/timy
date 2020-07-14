<?php

namespace Dainsys\Timy\Controllers;

use Carbon\Carbon;
use Dainsys\Timy\Disposition;
use Dainsys\Timy\Rules\DateRangeInDays;
use Dainsys\Timy\Exports\HoursExport;
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

        return view('timy::user-dashboard');
    }
    public function superAdmin()
    {
        if (Gate::denies(config('timy.roles.super_admin'))) {
            abort(403, 'Unauthorized');
        }

        $dispositions = Disposition::orderBy('name')->get();

        return view('timy::super-admin-dashboard', compact('dispositions'));
    }
    public function admin()
    {
        if (Gate::denies(config('timy.roles.admin'))) {
            abort(403, 'Unauthorized');
        }

        return view('timy::admin-dashboard');
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
