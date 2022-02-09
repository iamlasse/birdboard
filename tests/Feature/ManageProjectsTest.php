<?php

namespace Tests\Feature;

use App\Http\Controllers\ProjectsController;
use App\Models\Project;
use App\Models\User;
use Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{

  use WithFaker, RefreshDatabase;

  /**
   * @test
   */
  public function a_user_can_create_a_project()
  {
    $this->signIn();
    $this->get('/projects/create')->assertStatus(200);
    $this->followingRedirects()
      ->post('/projects', $attributes = Project::factory()->raw())
      ->assertSee($attributes['title'])
      ->assertSee($attributes['description'])
      ->assertSee($attributes['notes']);
  }

  /** @test */
  public function unauthorized_users_cannot_delete_projects(){
    // $this->withoutExceptionHandling();

    $project = app(ProjectFactory::class)->create();

    $this->delete($project->path())
    ->assertRedirect('/login');
    
    $user = $this->signIn();
    
    $this->delete($project->path())->assertStatus(403);
    
    $project->invite($user);
    
    $this->actingAs($user)->delete($project->path())->assertStatus(403);
  
  }

  /** @test */
  public function an_authenticated_user_can_delete_a_project(){
    $this->withoutExceptionHandling();

    $project = app(ProjectFactory::class)->create();

    $this->actingAs($project->owner)
          ->delete($project->path())
    ->assertRedirect('/projects');

    $this->assertDatabaseMissing('projects', $project->only('id'));
  }



  /** @test */
  public function a_user_can_update_their_project(){
    $this->withoutExceptionHandling();
    $project = app(ProjectFactory::class)->ownedBy($this->signIn())->create();
    $this->patch($project->path(),$attributes = [
      'title' => 'Title is updated',
      'description' => 'Description is updated',
      'notes' => 'General notes updated'
    ])
    ->assertRedirect($project->path());

    $this->assertDatabaseHas('projects', $attributes);
    
  }

  /** @test */
  public function guests_cannot_manage_projects(){
    
    $project = app(ProjectFactory::class)->create();

    $this->get('/projects')->assertRedirect('login');
    $this->get('/projects')->assertRedirect('login');
    // $this->get($project->path() . '/edit')->assertRedirect('login');
    $this->get($project->path())->assertRedirect('login');
    $this->post('/projects', $project->toArray())->assertRedirect('login');
  }

  /** @test */
  public function it_requires_a_title()
  {
    $this->actingAs(User::factory()->create());
    $attributes = Project::factory()->raw([
      'title' => '',
    ]);

    $this->post('/projects', $attributes)->assertSessionHasErrors(['title']);
  }

  /** @test */
  public function it_requires_a_description()
  {
    $this->signIn();
    $attributes = Project::factory()->raw([
      'description' => '',
    ]);

    $this->post('/projects', $attributes)->assertSessionHasErrors(['description']);
  }

  /** @test */
  public function a_user_can_view_their_project()
  {
    $project = app(ProjectFactory::class)->create();
    $this->actingAs($project->owner)
          ->get($project->path())
          ->assertSee($project->title)
          ->assertSee($project->description);
  }

  /** @test */
  public function only_an_authenticated_user_can_create_a_project()
  {
    $user = User::factory()->create();
    $attributes = Project::factory()->raw(['owner_id' => $user]);
    $this->post('/projects', $attributes)->assertRedirect('login');
  }

  /** @test */
  public function guests_cannot_view_projects()
  {
    $this->get('/projects')->assertRedirect('login');
  }

  /** @test */
  public function guests_cannot_view_a_single_project()
  {
    $project = Project::factory()->create();
    
    Auth::logout();
    $this->get($project->path())->assertRedirect('login');
  }

  /** @test */
  public function an_authenticated_user_cannot_view_others_projects()
  {
    $project = app(ProjectFactory::class)->create();
    
    $this->actingAs($this->signIn())->get($project->path())->assertStatus(403);

  }
  /** @test */
  public function an_authenticated_user_cannot_update_others_projects()
  {
// 

    $this->signIn();
    $project = app(ProjectFactory::class)->create();
    $this->patch($project->path(), ['notes' => 'Changed'])->assertStatus(403);
  }

  /** @test */
  public function a_user_can_see_all_the_projects_they_haev_been_invited_to(){
    $project = tap(app(ProjectFactory::class)->create())->invite($this->signIn());
    $this->get(action([ProjectsController::class, 'index']))->assertSee($project->title);
  }

  /** @test */
  public function tasks_can_be_saved_along_with_a_project_on_creation(){
    $this->withoutExceptionHandling();
    $this->signIn();
    $attributes = Project::factory()->raw();

    $attributes['tasks'] = [
      ['body' => 'Task 1'],
      ['body' => 'Task 2'],
    ];

    $this->post(route('projects.store'), $attributes);

    $this->assertCount(2, Project::first()->tasks);
  }
}
