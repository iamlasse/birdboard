<?php

namespace App\Honeypot\View\Components;

use Illuminate\View\Component;

/**
 * Honeypot Component
 */
class Honeypot extends Component
{
    public $inputName;
    public $inputTime;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inputName = config('honeypot.honeypot_field');
        $this->inputTime = config('honeypot.honeypot_time');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        
        return view('honeypot::honeypot');
    }
}
