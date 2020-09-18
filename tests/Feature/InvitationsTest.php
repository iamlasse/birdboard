<?php

namespace Tests\Feature;

use App\Http\Controllers\ProjectInvitesController;
use App\Http\Controllers\ProjectTasksController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /** @test */
    public function invited_users_can_update_projects_details()
    {
        $project = app(ProjectFactory::class)->create();

        $project->invite($otherUser = User::factory()->create());

        
        $this->actingAs($otherUser)->post(action([ProjectTasksController::class, 'store'], $project), $task = [
            'body' => 'Test Task'
        ]);

        $this->assertDatabaseHas('tasks', $task);
    }

    /** @test */
    public function a_project_owner_can_invite_a_user(){
        $project = app(ProjectFactory::class)->create();
        
        $invitedUser = User::factory()->create();

        $this->actingAs($project->owner)->post(action([ProjectInvitesController::class, 'store'], $project), $invites = [
            'email' => $invitedUser->email
        ])
        ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($invitedUser));
    }
    
    /** @test */
    public function the_invited_email_address_must_belong_to_a_user(){
        $this->withExceptionHandling();
        $project = app(ProjectFactory::class)->create();
        
        $this->actingAs($project->owner)->post(action([ProjectInvitesController::class, 'store'], $project), $invites = [
            'email' => 'notuser@test.com'
        ])
        ->assertSessionHasErrors([
            'email' => 'There is no user with this email address'
        ]);
    }

    /** @test */
    public function a_user_can_only_be_invited_once(){
        $project = app(ProjectFactory::class)->create();
        
        $this->actingAs($project->owner)->post(action([ProjectInvitesController::class, 'store'], $project), $invites = [
            'email' => $email = User::factory()->create()->email
        ]);
        $this->actingAs($project->owner)->post(action([ProjectInvitesController::class, 'store'], $project), $invites = [
            'email' => $email
        ])->assertSessionHasErrors([
            'email' => 'You have already invited a user with this email'
        ]);

        
    }

    /** @test */
    public function only_the_project_owner_can_invite_users(){
        $project = app(ProjectFactory::class)->create();
        $notOwner = User::factory()->create();

        $this->actingAs($notOwner)->post(action([ProjectInvitesController::class, 'store'], $project), $invites = [
            'email' => 'notuser@test.com'
        ])
        ->assertStatus(403);
      
    }
}
