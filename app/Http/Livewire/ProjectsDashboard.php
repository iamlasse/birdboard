<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProjectsDashboard extends Component
{
    public function render()
    {
        return view('livewire.projects.dashboard')->extends('layouts.app')->section('content');
    }
}
