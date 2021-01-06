<?php

namespace Dainsys\Timy\Console;

use App\User;
use Dainsys\Timy\Notifications\PreviousDateHoursReport as NotificationsPreviousDateHoursReport;
use Dainsys\Timy\Repositories\Hours\PayableForDate;
use Illuminate\Console\Command;

class PreviousDateHoursReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timy:previous-date-hours-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a summary report with the total payable hours each user worked the previous day!';

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

        $previousDateHours = (new PayableForDate(now()->subDay()))
            ->getTotal()
            ->filter(function ($user) {
                return $user->total_hours > 0;
            });

        if ($previousDateHours->count() > 0 && $notifyableUsers->count() > 0) {
            $notifyableUsers
                ->each
                ->notify(new NotificationsPreviousDateHoursReport($previousDateHours));
        }
    }
}
