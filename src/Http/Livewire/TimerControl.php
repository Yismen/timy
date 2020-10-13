<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Repositories\DispositionsRepository;
use Dainsys\Timy\Resources\TimerResource;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class TimerControl extends Component
{
    public $current_disposition_id;

    public $dispositions = [];

    public $selectedDisposition;

    public $running = [];
    
    public $user;

    public function mount()
    {
        $this->current_disposition_id = $this->getCurrentDispositionId();

        $this->user = auth()->user();
        
        if ($timer = $this->user->timers()->running()->first()) {
            $this->running = TimerResource::make($timer)->jsonSerialize();
        } else {
            $this->running = $this->user->startTimer($this->current_disposition_id);
        }

        $this->dispositions = DispositionsRepository::all();
    }

    public function render()
    {
        return view('timy::livewire.timer-control');
    }

    public function getListeners()
    {
        return [
            'echo-private:Timy.User.'. $this->user->id . ',TimerCreated' => 'timerUpdatedRemotedly',
        ];
    }


    public function updateUserDisposition()
    {
        try {
            $this->running  = $this->user->startTimer($this->selectedDisposition);
        } catch (\Throwable $th) {
            $code = (int) $th->getCode();
            return response()->json([
                'user' => $this->user,
                'message' => $th->getMessage(),
                'exception' => get_class($th)
            ], $code > 0 ? $code : 500);
        }
    }

    public function timerUpdatedRemotedly()
    {
        dd("asdfasdf");
    }

    protected function getCurrentDispositionId()
    {
        return $this->running ?
            $this->running->disposition_id :
            Cache::get('timy-user-last-disposition-' . auth()->id(), config('timy.default_disposition_id'));
    }
    
}
