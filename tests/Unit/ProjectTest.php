<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ProjectTest extends TestCase
{
  use RefreshDatabase;
  /** @test */
  public function it_has_a_path()
  {
    $user = User::factory()->create();
    $project = Project::factory()->create(['owner_id' => $user]);
    $this->assertEquals('/projects/' . $project->id, $project->path());
  }

  /** @test */
  public function a_project_has_an_owner()
  {
    $user = User::factory()->create();
    $project = Project::factory()->create(['owner_id' => $user]);
    $this->assertInstanceOf(User::class, $project->owner);
  }

  /** @test */
  public function it_can_add_a_task(){
    $this->signIn();
    $project = Project::factory()->create();
    $task = $project->addTask(['body' => 'Test Task']);

    $this->assertCount(1, $project->tasks);

    $this->assertDatabaseHas('tasks', ['body' => 'Test Task']);
    $this->assertTrue($project->tasks->contains($task));
  }  

  /** @test */
  public function it_can_invite_a_user(){
    $project = Project::factory()->create();

    $project->invite($user = User::factory()->create());

    $this->assertTrue($project->members->contains($user));
  }
}
