<?php

namespace Dainsys\Timy\Console;

use App\User;
use Dainsys\Timy\Repositories\Hours\PayableToday;
use Dainsys\Timy\Notifications\UsersWithTooManyHours as NotificationsUsersWithTooManyHours;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UsersWithTooManyHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timy:users-with-too-many-hours';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a notification with a list of users who have passed the daily hours threshold';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notifyableUsers = User::isTimyAdmin()->get();
        $usersWithTooManyHours = (new PayableToday())->overDailyThreshold();
        $cacheKey = 'timy-users-with-too-hours-' . date('Y-m-d') . '-' . number_format($usersWithTooManyHours->sum('total_hours'), 2);

        if (
            !Cache::has($cacheKey) &&
            $usersWithTooManyHours->count() > 0 &&
            $notifyableUsers->count() > 0
        ) {
            Cache::put($cacheKey, 1, now()->addDay());

            $notifyableUsers
                ->each
                ->notify(new NotificationsUsersWithTooManyHours($usersWithTooManyHours));
        }
    }
}
