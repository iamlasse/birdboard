<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Setup\ProjectFactory;
use Tests\TestCase;

class RecordActivityTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function when_creating_a_project()
    {
        $project = app(ProjectFactory::class)->create();

        $this->assertCount(1, $project->activity);

        
        tap($project->activity->last(), function($activity) {
            $this->assertEquals('created_project', $activity->description);
            
            $this->assertNull($activity->changes);
        });

    }

    /** @test */
    public function when_updating_a_project(){
        $project = app(ProjectFactory::class)->create();
        $originalTitle = $project->title;
        $project->update(['title' => 'Changed']);
        
        $this->assertCount(2, $project->activity);
        
        tap($project->activity->last(), function($activity) use ($project) {
            $this->assertEquals('updated_project', $activity->description);
            $expected = [
                'before' => collect($project->oldAttributes)->except(['updated_at', 'created_at', 'owner_id', 'id'])->toArray(),
                'after' => collect($project->getChanges())->except(['updated_at', 'created_at', 'owner_id', 'id'])->toArray()
            ];
            $this->assertEquals($expected, $activity->changes);
        });
        
    }
    
    /** @test */
    public function when_creating_a_new_task(){
        $this->withoutExceptionHandling();
        $project = app(ProjectFactory::class)->ownedBy($this->signIn())->create();
        
        $project->addTask(['body' => 'Test Task']);
        
        $this->assertCount(2, $project->activities);
        $this->assertEquals('created_task', $project->activities->last()->description);
        tap($project->activities->last(), function($activity) {
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('Test Task', $activity->subject->body);
        });
    }
    
    /** @test */
    public function when_commpleting_a_task(){
        $this->withoutExceptionHandling();
        $project = app(ProjectFactory::class)->withTasks(1)->create();
        
        $this->actingAs($project->owner)->patch($project->tasks()->first()->path(), [
            'completed' => true
        ]);
        
        // $project->refresh();
        // dump($project->activities);
        $this->assertCount(3, $project->activities);
        $this->assertEquals('completed_task', $project->activities->last()->description);

    }
    /** @test */
    public function when_incommpleting_a_task(){
        $this->withoutExceptionHandling();
        $project = app(ProjectFactory::class)->create();

        $project->addTask([
            'body' => 'Test task',
            'completed' => false
        ]);

        
        $this->actingAs($project->owner)->patch($project->tasks()->first()->path(), [
            'completed' => true
            ]);
        
        $this->assertCount(3, $project->activities);
        
        // dump($project->activity->pluck('description'));
        // dump($project->activity->last()->description);
        $this->assertEquals('completed_task', $project->activities->last()->description);
        
        $project->refresh();
        
        $this->actingAs($project->owner)->patch($project->tasks()->first()->path(), [
            'completed' => false
            ]);
            $project->refresh();
            $this->assertCount(4, $project->activities);
        // dump($project->activity->map(function($activity) {
        //     return [
        //         'description' => $activity->description,
        //         'created' => $activity->created_at->format('Y-m-d\TH:i:s.uP T')
        //     ];
        // }));
        // dump($project->activity->last()->description);
        $this->assertEquals('marked_task_incomplete', $project->activities->last()->description);
                
    }
            
        /** @test */
    public function when_deleting_a_task(){
        $project = app(ProjectFactory::class)->withTasks(1)->create();

        $project->tasks[0]->delete();
        $this->assertCount(3, $project->activities);
    }
}
