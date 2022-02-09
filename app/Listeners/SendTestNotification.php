<?php

namespace App\Listeners;

use App\Events\ProjectUpdated;
use App\Notifications\SomeNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendTestNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProjectUpdated $event
     * @return void
     */
    public function handle(ProjectUpdated $event)
    {
        Notification::send($event->project->members, new SomeNotification($event->project));
    }
}
