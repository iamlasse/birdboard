<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = Project::all()->pluck('id');

        $projects->each(function ($project) {
            Task::factory()->count(10)->create([
                'project_id' => $project,
                'user_id' => User::inRandomOrder()->first()->id
            ]);
        });
    }
}
