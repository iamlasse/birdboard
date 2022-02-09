<?php

namespace App\Http\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class DataTable extends Component
{
    use WithPagination;

    public $search;
    public $showProjectUpdatedNotification = false;
    public $project;
    public $showDropdown = false;
    protected $queryString = [      
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    


    public function mount(Project $project)
    {
        $this->fill(request()->only('search', 'page'));
        $this->project = $project;
    }

    

    public function render()
    {
        return view(
            'livewire.data-table', [
            'projects' => auth()->user()
                ->authorizedProjects()
                ->search($this->search)
                ->taskStatus()
                ->counts()
            // ->projectTasks(false)
            // ->projectTasks(true)
                ->paginate(10)
            // ->get()
            ]
        );
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
