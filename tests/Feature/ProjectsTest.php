<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{

  use WithFaker, RefreshDatabase;

  /**
   * @test
   */
    public function a_user_can_create_a_project()
    {
    $this->withoutExceptionHandling();
      $attributes = [
        'title' => $this->faker->word(3),
        'description' => $this->faker->paragraph(3)
      ];

      $project = $this->post('/projects', $attributes)->assertRedirect('/projects');

      $this->assertDatabaseHas('projects', $attributes);

      $this->get('/projects')->assertSee($attributes['title']);
    }
}
