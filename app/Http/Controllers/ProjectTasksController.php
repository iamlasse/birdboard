<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Events\ProjectUpdated;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update', $project);
        $project->addTask(request()->validate(['body' => 'required']));
        ProjectUpdated::broadcast($project);
        return back();
    }

    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $task->project);
        $task->update(request()->validate(['body' => 'sometimes|required']));
        
        if(request('completed') !== $task->completed) {
            request('completed') ? $task->complete() : $task->incomplete();
        }

        ProjectUpdated::broadcast($project);
        
        return redirect($project->path());
    }
}
