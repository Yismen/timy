<?php

namespace Dainsys\Timy\Repositories;

use Dainsys\Timy\Models\QueryFilters\Disposition;
use Dainsys\Timy\Models\QueryFilters\FromDate;
use Dainsys\Timy\Models\QueryFilters\Invoiceable;
use Dainsys\Timy\Models\QueryFilters\Payable;
use Dainsys\Timy\Models\QueryFilters\Running;
use Dainsys\Timy\Models\QueryFilters\ToDate;
use Dainsys\Timy\Models\QueryFilters\User;
use Dainsys\Timy\Models\Timer;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Cache;

class TimersRepository
{
    public static function all()
    {
        return Cache::rememberForever('timy.timers', function () {
            return Timer::with(['user.timy_team', 'disposition'])
                ->running()
                ->orderBy('disposition_id')
                ->orderBy('name')
                ->get();
        });
    }

    public static function filtered()
    {
        $timers = app(Pipeline::class)
            ->send(Timer::query())
            ->through([
                FromDate::class,
                ToDate::class,
                Disposition::class,
                Payable::class,
                Invoiceable::class,
                User::class,
                Running::class,
            ])
            ->thenReturn();

        return $timers->get();
    }
}
