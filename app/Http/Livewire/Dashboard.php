<?php

namespace App\Http\Livewire;

use App\Models\Project;
use Livewire\Component;

class Dashboard extends Component
{
    
    public $showNotification = false;
    public $project;
    // public $listeners;

    // protected $listeners = 

    public function getListeners()
    {
        return auth()->user()
            ->projects()
            ->get()
            ->pluck('id')
            ->reduce(
                function ($carry, $id) {
                    $carry["echo:projects.{$id},ProjectUpdated"] = 'notifyProjectUpdates';
                    return $carry;
                }, []
            );
    }


    public function mount(Project $project)
    {    
        $this->project = $project;
        // $this->listeners = ;
    }

    public function notifyProjectUpdates(Project $project)
    {
        $this->project = $project;
        $this->showNotification = true;
        session()->flash('message', 'Project successfully updated.' . $project->id);
        $this->emit('projectUpdated:'.$project->id, $project);
    }

    public function render()
    {
        return view(
            'livewire.dashboard', [
            'listeners' => $this->getListeners()
            ]
        )->extends('layouts.app')->section('content');
    }
}
