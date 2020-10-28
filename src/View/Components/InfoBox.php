<?php

namespace Dainsys\Timy\View\Components;

use Illuminate\View\Component;

class InfoBox extends Component
{


    public $number;

    public $title;

    public $tooltip;

    public $class;

    public function __construct($number, $title, $tooltip = '', $class = '')
    {
        $this->number = $number;
        $this->title = $title;
        $this->tooltip = $tooltip;
        $this->class = $class;
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
