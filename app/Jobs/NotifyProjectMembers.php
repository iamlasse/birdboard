<?php

namespace App\Jobs;

use App\Models\Project;
use App\Mail\ProjectUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use App\Notifications\SomeNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class NotifyProjectMembers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project; 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->project->members)
            // ->cc($moreUsers)
            // ->bcc($evenMoreUsers)
            ->queue(new ProjectUpdate($this->project));
    }
}
