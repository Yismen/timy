<?php

namespace Dainsys\Timy\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Dainsys\Timy\Charts\UserDailyHoursChart;
use Dainsys\Timy\Disposition;
use Dainsys\Timy\Rules\DateRangeInDays;
use Dainsys\Timy\Exports\HoursExport;
use Dainsys\Timy\Repositories\DispositionsRepository;
use Dainsys\Timy\Repositories\UserDataRepository;
use Dainsys\Timy\Repositories\UserHoursDaily;
use Dainsys\Timy\Resources\UserTimerResource;
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

        $daily_hours =  UserTimerResource::collection(UserHoursDaily::get(auth()->user()))->pluck('hours', 'date');

        $chart = new UserDailyHoursChart;
        $chart->labels($daily_hours->keys());
        $chart->dataset(__('timy::titles.daily_hours'), 'bar', $daily_hours->values())
            ->color('rgba(21,101,192 ,1)')
            ->backgroundColor('rgba(21,101,192 ,.25)');

        return view('timy::dashboards.user', [
            'chart' => $chart
        ]);
    }

    public function superAdmin()
    {
        if (Gate::denies(config('timy.roles.super_admin'))) {
            abort(403, 'Unauthorized');
        }

        $dispositions = DispositionsRepository::all();

        return view('timy::dashboards.super-admin', compact('dispositions'));
    }
    public function admin()
    {
        if (Gate::denies(config('timy.roles.admin'))) {
            abort(403, 'Unauthorized');
        }

        return view('timy::dashboards.admin', [
            'users' => resolve('TimyUser')->orderBy('name')
                ->isTimyUser()
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
            'data' => array_merge(UserDataRepository::toArray($user), ['hours_daily' => UserHoursDaily::get($user)])
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
