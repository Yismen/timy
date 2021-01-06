<?php

namespace Dainsys\Timy\Console\Commands;

use App\User;
use Dainsys\Timy\Repositories\Hours\PayableToday;
use Dainsys\Timy\Notifications\UsersWithTooManyHours as NotificationsUsersWithTooManyHours;
use Illuminate\Console\Command;

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

        if ($usersWithTooManyHours->count() > 0 && $notifyableUsers->count() > 0) {
            $notifyableUsers
                ->each
                ->notify(new NotificationsUsersWithTooManyHours($usersWithTooManyHours));
        }
    }
}
