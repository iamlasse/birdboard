<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        $this->authorize('update', $project);
        $project->addTask(request()->validate(['body' => 'required']));

        return back();
    }

    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $task->project);
        $task->update(request()->validate(['body' => 'sometimes|required']));
        
        if(request('completed') !== $task->completed) {
            request('completed') ? $task->complete() : $task->incomplete();
        }
        
        return redirect($project->path());
    }
}
