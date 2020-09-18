<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function guests_cannot_add_tasks_to_projects(){
      $project = Project::factory()->create();
      Auth::logout();
      $this->post($project->path() . '/tasks')->assertRedirect('login');
    }
    
    /** @test */
    public function only_a_project_owner_can_add_tasks(){
        
      $project = app(ProjectFactory::class)->create();

        $this->actingAs($this->signIn())->post($project->path() . '/tasks', ['body' => 'Test Task'])
            ->assertStatus(403);

            $this->assertDatabaseMissing('tasks', ['body' => 'Test Task']);
      
    }
    
    /** @test */
    public function a_project_can_have_tasks(){

        // Given I am signed in
        // And I can access my project
        $project = app(ProjectFactory::class)
                    ->create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', ['body' => 'Test Task']);

        $this->get($project->path())->assertSee('Test Task');

    }

    /** @test */
    public function a_task_requires_a_body(){

      $project = app(ProjectFactory::class)
                ->ownedBy($this->signIn())
                ->create();
      $attributes = Task::factory()->raw(['body' => null]);

      $this->post($project->path() . '/tasks', $attributes)
        ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_task_can_be_updated_by_the_project_owner(){

      $project = app(ProjectFactory::class)
                    ->withTasks(1)
                    ->create();
        
      $this->actingAs($project->owner)->patch($project->tasks->first()->path(), $attributes = [
        'body' => 'Some new task'
        ]);
        
      $this->assertDatabaseHas('tasks', $attributes);
    }
    
    /** @test */
    public function a_task_can_be_completed_by_the_project_owner(){

      $this->withoutExceptionHandling();
      $project = app(ProjectFactory::class)
                    ->withTasks(1)
                    ->create();
        
      $this->actingAs($project->owner)->patch($project->tasks->first()->path(), [
        'body' => 'Some new task',
        'completed' => true
        ]);
        
      $this->assertDatabaseHas('tasks', [
          'body' => 'Some new task'
      ]);
    }
   
    /** @test */
    public function a_task_can_be_marked_as_incomplete_by_the_project_owner(){

      $project = app(ProjectFactory::class)
                    ->withTasks(1)
                    ->create();
        
      $this->actingAs($project->owner)->patch($project->tasks->first()->path(), [
        'body' => 'Some new task',
        'completed' => true
        ]);
      
        $this->actingAs($project->owner)->patch($project->tasks->first()->path(), [
        'body' => 'Some new task',
        'completed' => false
        ]);
        
      $this->assertDatabaseHas('tasks', [
          'body' => 'Some new task',
          'completed' => false
      ]);
    }

    /** @test */
    public function a_task_cannot_be_updated_by_anyone_other_than_project_owner(){

      $project = app(ProjectFactory::class)
                ->withTasks(1)
                ->create();

      $this->actingAs($this->signIn())->patch($project->tasks->first()->path(), ['body' => 'changed'])
        ->assertStatus(403);

      $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }
}
