<?php

namespace Dainsys\Timy\Http\Livewire;

use Livewire\Component;

class InfoBox extends Component
{
    public $title;

    public $data;

    public $tooltip;

    public function mount($title, $data, $tooltip = '')
    {
        $this->title = $title;
        $this->data = $data;
        $this->tooltip = $tooltip;
    }

    public function render()
    {
        return view('timy::livewire.info-box');
    }
}
