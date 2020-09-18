<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{
    /**
     * Handle the project "created" event.
     *
     * @param  \App\Models\Project $project
     * @return void
     */
    public function created(Project $project)
    {
        $project->recordActivity('Created Project');
    }

    /**
     * Handle the project "updated" event.
     *
     * @param  \App\Models\Project $project
     * @return void
     */
    public function updated(Project $project)
    {
        $project->recordActivity('Updated Project');
    }    
}
