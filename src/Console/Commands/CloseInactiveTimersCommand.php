<?php

namespace Dainsys\Timy\Console\Commands;

use Dainsys\Timy\Events\TimerCreatedAdmin;
use Dainsys\Timy\Events\TimerStopped;
use Dainsys\Timy\Models\Timer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CloseInactiveTimersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timy:close-inactive-timers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if ip address of the timers in ope status is still alive, otherwise remove their timers!';

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
        if (!(bool)config('timy.with_scheduled_commands')) {
            return 0;
        }

        $this->getOpenTimers()
            ->each(function ($timer) {
                try {
                    Http::timeout((int) config('timy.ip_timeout_in_seconds', 5))->get($timer->ip_address);

                    $this->info("User ip {$timer->ip_address} is Alive");
                } catch (\Throwable $th) {
                    $user = $timer->user;

                    $timer->stop();

                    $user->forgetTimyCache();

                    event(new TimerStopped($user));
                    event(new TimerCreatedAdmin($user));

                    $this->error("User ip {$timer->ip_address} is Dead");
                }
            });
    }

    protected function getOpenTimers()
    {
        return Timer::running()
            ->with('user')->get();
    }
}
