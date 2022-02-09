<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectUpdate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $changes = collect($this->project->activities()->latest('updated_at')->first()->changes);
        
        return $this->markdown('mail.projects.updated')->with(
            [
            'project' => $this->project,
            'changes' => $changes->isNotEmpty() ? collect($changes->get('after'))->diff($changes->get('before')) : [],
            'before' => $changes->get('before') ?? [],
            'after' => $changes->get('after')?? []
            ]
        );
    }
}
