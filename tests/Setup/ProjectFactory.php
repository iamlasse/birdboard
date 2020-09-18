<?php
namespace Tests\Setup;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ProjectFactory
{
  protected $tasksCount = 0;
  protected $user;
  public function create($attributes = [])
  {
   $project = Project::factory()->create(
     array_merge($attributes, [
      'owner_id' => $this->user ?? User::factory()
    ]));

    Task::factory()->count($this->tasksCount)->create([
      'project_id' => $project->id
      ]);

      return $project;
  }

  public function withTasks(int $count)
  {
    $this->tasksCount = $count;
    return $this;
  }

  public function ownedBy(User $user)
  {
    $this->user = $user;
    return $this;
  }
}

