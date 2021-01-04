<?php

namespace Dainsys\Timy\Console\Commands;

use App\User;
use Dainsys\Timy\Events\TimerCreatedAdmin;
use Dainsys\Timy\Events\TimerStopped;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Notifications\TimersRunningForTooLong as NotificationsTimersRunningForTooLong;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TimersRunningForTooLong extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timy:timers-running-for-too-long';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if running timers have been running for too long based on the hours threshold!';

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

        $runningTimers = Timer::runningForTooLong()->get();

        if ($runningTimers->count() > 0 && $notifyableUsers->count() > 0) {
            $notifyableUsers
                ->each
                ->notify(new NotificationsTimersRunningForTooLong($runningTimers));
        }
    }
}
