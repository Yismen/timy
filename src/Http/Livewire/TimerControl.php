<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Exceptions\ShiftEndendException;
use Dainsys\Timy\Repositories\DispositionsRepository;
use Dainsys\Timy\Resources\TimerResource;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TimerControl extends Component
{
    public $pushed = "Initial";

    public $dispositions = [];

    public $selectedDisposition;

    public $running = [];

    public $user;

    public function mount()
    {
        $this->user = auth()->user();

        $this->selectedDisposition = $this->selectedDisposition == null ?
            $this->getCurrentDispositionId() :
            $this->selectedDisposition;

        $this->user->timers()->running()->get()->each->stop();

        $this->createNewTimerForUser($this->selectedDisposition);

        // if ($runningTimer) {
        //     $this->selectedDisposition = $runningTimer->disposition_id;
        //     $this->running = TimerResource::make($runningTimer)->jsonSerialize();
        // } else {
        //     $this->selectedDisposition = $this->selectedDisposition == null ?
        //         $this->getCurrentDispositionId() :
        //         $this->selectedDisposition;

        //     $this->createNewTimerForUser($this->selectedDisposition);
        // }
    }

    public function render()
    {
        $this->dispositions = DispositionsRepository::all();
        $this->emit('timerCreatedByTimerControl', $this->running);

        return view('timy::livewire.timer-control');
    }

    protected function getListeners()
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

        $this->dispatchBrowserEvent('timyShowAlert', ['message' => trans('timy::titles.updated_remotedly')]);
    }

    public function timerStoppedRemotedly($payload)
    {
        $this->running = $payload['timer'];
        $this->selectedDisposition =  $this->getCurrentDispositionId();

        $this->dispatchBrowserEvent('timyShowAlert', ['message' => trans('timy::titles.stopped_remotedly')]);
    }

    protected function getCurrentDispositionId()
    {
        return Cache::get('timy-user-last-disposition-' . auth()->id(), config('timy.default_disposition_id'));
    }

    protected function createNewTimerForUser($dispositionId)
    {
        try {
            $this->running =  $this->user->startTimer($dispositionId);

            $this->emit('timerCreated');
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('timyShowAlert', ['message' => $th->getMessage()]);
            if ($th instanceof ShiftEndendException) {

                $this->selectedDisposition =  $this->getCurrentDispositionId();
            }
            $this->running = [];
        }
    }
}
