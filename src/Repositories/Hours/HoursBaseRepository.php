<?php

namespace Dainsys\Timy\Repositories\Hours;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

abstract class HoursBaseRepository
{
    protected $date;

    public function __construct(Carbon $date = null)
    {
        $this->date = $date ?: now();
    }

    abstract public function getTotal();

    public function overDailyThreshold()
    {
        $threshold = config('timy.daily_hours_threshold');

        return $this->getTotal()
            ->filter(function ($user) use ($threshold) {
                return $user->total_hours > $threshold;
            });
    }

    protected function baseQuery(): Collection
    {
        return User::whereHas('timy_role')->get();
    }
}
