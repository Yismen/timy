<?php

namespace Dainsys\Timy\View\Components;

use Illuminate\View\Component;

class InfoBox extends Component
{


    public $number;

    public $title;

    public $tooltip;

    public function __construct($number, $title, $tooltip = '')
    {
        $this->number = $number;
        $this->title = $title;
        $this->tooltip = $tooltip;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('timy::components.info-box');
    }
}
