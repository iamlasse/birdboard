<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Task;
use App\Observers\ProjectObserver;
use App\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Project::observe(ProjectObserver::class);
        // Task::observe(TaskObserver::class);

        Relation::morphMap([
            'project' => 'App\Models\Project',
            'task' => 'App\Models\Task',
        ]);
    }
}
