<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Exceptions\ShiftEndendException;
use Dainsys\Timy\Repositories\DispositionsRepository;
use Livewire\Component;

class TimerControl extends Component
{
    public $dispositions = [];

    public $selectedDisposition;

    public $running = [];

    public $user;

    protected $cached_timy_dispo;

    public function mount()
    {
        $this->user = auth()->user();
        $this->cached_timy_dispo = $this->user->getTimyCachedDispo();

        $this->selectedDisposition = $this->selectedDisposition == null ?
            $this->cached_timy_dispo :
            $this->selectedDisposition;

        $this->user->timers()->running()->get()->each->stop();
        $this->createNewTimerForUser();
    }

    public function render()
    {
        $this->dispositions = DispositionsRepository::all();

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
        $this->createNewTimerForUser();
    }

    public function timerUpdatedRemotedly($payload)
    {
        $this->running = $payload['timer'];
        $this->selectedDisposition = $payload['timer']['disposition_id'];

        $this->emit('timerCreatedByTimerControl', $this->running);
        $this->dispatchBrowserEvent('showTimyAlert', ['message' => trans('timy::titles.updated_remotedly')]);
    }

    public function timerStoppedRemotedly($payload)
    {
        $this->running = $payload['timer'];
        $this->selectedDisposition =  $this->user->getTimyCachedDispo();

        $this->emit('timerCreatedByTimerControl', $this->running);
        $this->dispatchBrowserEvent('showTimyAlert', ['message' => trans('timy::titles.stopped_remotedly')]);
    }

    public function createNewTimerForUser()
    {
        try {
            $this->running =  $this->user->startTimer($this->selectedDisposition);
            $this->emit('timerCreatedByTimerControl', $this->running);
        } catch (ShiftEndendException $th) {
            $this->selectedDisposition =  $this->cached_timy_dispo;
            $this->running = [];
            $this->dispatchBrowserEvent('showTimyAlert', ['message' => $th->getMessage()]);
        } catch (\Exception $th) {
            $this->running = [];
            $this->dispatchBrowserEvent('showTimyAlert', ['message' => $th->getMessage()]);
            $this->dispatchBrowserEvent('reloadPage');
        }
    }
}
