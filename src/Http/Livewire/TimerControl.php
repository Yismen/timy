<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Repositories\DispositionsRepository;
use Dainsys\Timy\Resources\TimerResource;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TimerControl extends Component
{
    public $exception;

    public $pushed = "Initial";

    public $dispositions = [];

    public $selectedDisposition;

    public $running = [];

    public $user;

    public function mount()
    {
        $this->user = auth()->user();
        $runningTimer = $this->user->timers()->running()->first();

        if ($runningTimer) {
            $this->selectedDisposition = $runningTimer->disposition_id;
            $this->running = TimerResource::make($runningTimer)->jsonSerialize();
        } else {
            $this->selectedDisposition = $this->getCurrentDispositionId();
            $this->createNewTimerForUser($this->selectedDisposition);
        }
    }

    public function render()
    {
        $this->dispositions = DispositionsRepository::all();

        return view('timy::livewire.timer-control');
    }

    public function getListeners()
    {
        return [
            "echo-private:Timy.User.{$this->user->id},\\Dainsys\\Timy\\Events\\TimerCreated" => 'timerUpdatedRemotedly',
            "echo-private:Timy.User.{$this->user->id},\\Dainsys\\Timy\\Events\\TimerStopped" => 'timerStoppedRemotedly',
        ];
    }

    public function updateUserDisposition()
    {
        $this->createNewTimerForUser($this->selectedDisposition);
    }

    public function timerUpdatedRemotedly($payload)
    {
        $this->running = $payload['timer'];
        $this->selectedDisposition = $this->running['disposition_id'];
    }

    public function timerStoppedRemotedly($payload)
    {
        $this->running = $payload['timer'];
        $this->selectedDisposition = $this->getCurrentDispositionId();
    }

    protected function getCurrentDispositionId()
    {
        return Cache::get('timy-user-last-disposition-' . auth()->id(), config('timy.default_disposition_id'));
    }

    protected function createNewTimerForUser($dispositionId)
    {
        try {
            $timer =  $this->user->startTimer($dispositionId);
            $this->running = $timer;
        } catch (\Throwable $th) {
            $this->exception = $th->getMessage();
            $this->running = [
                "id" => '',
                "user_id" => '',
                "user_created_at" => '',
                "name" => '',
                "path" => '',
                "disposition_id" => '',
                "disposition" => '',
                "started_at" => '',
                "finished_at" => '',
                "is_payable" => '',
                "is_invoiceable" => '',
                "total_hours" => '',
                "payable_hours" => '',
                "invoiceable_hours" => '',
            ];
        }
    }
}
