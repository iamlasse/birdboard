<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
          'title' => $this->faker->sentence,
          'description' => $this->faker->paragraph(3),
          'notes' => $this->faker->sentence,
          'owner_id' => function () {
              if(auth()->user()) {
                  return auth()->user();
              }
              
              $all = User::all();
              if($all->count() === 0){
                  $user = User::factory()->create();
              } else {
                  $user = ($all)->random();
              }
            //   Auth::login($user);
              return $user;
          }
        ];
    }
}
