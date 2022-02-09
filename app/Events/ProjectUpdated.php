<?php

namespace App\Events;

use App\Models\Project;
use App\Jobs\NotifyProjectMembers;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProjectUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $project;

    public function __construct(Project $project)
    {
        $this->project = $project->load('tasks');
        $this->dontBroadcastToCurrentUser();
        NotifyProjectMembers::dispatch($project)->delay(now()->addSeconds(5))->onQueue('email');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\PrivateChannel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('projects.' .  $this->project->id);
    }
}
