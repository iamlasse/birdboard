<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Task;
// use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectsController extends Controller
{
    public function index()
    {
        return Inertia::render('Projects/Index', [
          'projects' => function() {
            return auth()->user()->authorizedProjects()->with(['tasks', 'owner'])->get();
          }
        ]);

    }

    public function store()
    {
        $validated = $this->validateRequest();
        $project = auth()->user()->projects()->create($validated);

        return redirect($project->path());
    }

    public function show(Project $project)
    {

        $this->authorize('update', $project);
        return Inertia::render(
            'Projects/Show', [
            'project' => function () use ($project) {
                return $project->load(
                    ['tasks', 'activities' => function ($query) {
                        $query->with(['subject', 'user']);
                    }]
                );
            }
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
    public function update(UpdateProjectRequest $request)
    {
    
        return redirect($request->save()->path());
    }

    public function destroy(Project $project)
    {
      $this->authorize('update', $project);
      $project->delete();
      return redirect('/projects');
    }

    protected function validateRequest()
    {
        return request()->validate(
            [
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable|min:10|max:400'
            ]
        );
    }
    
}
