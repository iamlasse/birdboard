<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the task "created" event.
     *
     * @param  \App\Models\Task $task
     * @return void
     */
    public function created(Task $task)
    {
        $task->recordActivity('Created Task');
    }

    /**
     * Handle the task "updated" event.
     *
     * @param  \App\Models\Task $task
     * @return void
     */
    public function updated(Task $task)
    {
        $task->wasChanged('body') && $task->recordActivity('Updated Task');
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Models\Task $task
     * @return void
     */
    public function deleted(Task $task)
    {
        $task->recordActivity('Deleted Task');
    }
}
