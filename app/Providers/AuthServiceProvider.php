<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Project' => 'App\Policies\ProjectPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        // Gate::define('edit-tasks', function ($user, Project $project, Task $task) {
        //     return ($user->id === $project->owner->id && $task->project->id === $project->id) ? Response::allow() : Response::deny('You must be the project owner or administrator.');
        // });
    }
}
