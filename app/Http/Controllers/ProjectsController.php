<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Events\ProjectUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\UpdateProjectRequest;

class ProjectsController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'Projects/Index',
            [
            'projects' => function () {
                // return Cache::remember("user.{auth()->id()}.projects", 60, function () {
                   return auth()->user()->authorizedProjects()->with(['tasks', 'owner'])->get();
                // });
            }
            ]
        );
    }

    public function store()
    {
        
        $validated = $this->validateRequest();
        $project = auth()->user()->projects()->create($validated);

        if ($tasks = request('tasks')) {
            $project->addTasks(
                collect($tasks)->filter(
                    function ($task) {
                        return $task['body'] != '';
                    }
                )->toArray()
            );
        }

        Cache::put("user.{auth()->id()}.projects", auth()->user()->authorizedProjects()->with(['tasks', 'owner'])->get(), now()->addMinutes(10));

        return redirect($project->path());
    }

    /**
     * Show Project
     *
     * @param  Project $project
     * @return void
     */
    public function show(Request $request, Project $project)
    {
        $this->authorize('show', $project);

        return Inertia::render(
            'Projects/Show',
            [
            'project' => function () use ($project) {
                // return Cache::remember("projects.{$project->id}", 30*60, function () use ($project) {
                    return $project->load(
                        [
                        'owner',
                        'activities',
                        'tasks' => function($query) {
                            return $query->latest();
                        },
                        'members',
                        ]
                    )->loadCount(
                        [
                            'activities' => function ($query) use ($project) {
                                $query->where('project_id', $project->id);
                            },
                            'tasks' => function ($query) use ($project) {
                                $query->where('project_id', $project->id);
                            }
                            ]
                    );
                // });
            },

            ]
        );
    }

    public function create()
    {
        return Inertia::render('Projects/Create');
    }

    /**
     * Update
     *
     * @param  UpdateProjectRequest $request
     * @param  Project              $project
     * @return redirect
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        return redirect($request->save()->path());
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);
        $project->delete();
        return redirect('/projects');
    }

    protected function validateRequest()
    {
        return request()->validateWithBag(
            'project',
            [
            'title' => 'sometimes|required',
            'description' => 'sometimes|required|min:10|max:300',
            'notes' => 'nullable|min:10|max:300'
            ]
        );
    }
}
