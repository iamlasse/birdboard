<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::factory()->createMany([
            [
            'name' => 'standard'
            ],
            [
            'name' => 'premium'
            ]
            ,[
            'name' => 'vip'
            ]
        ]);

        User::all()->each(function ($user) {
            $user->subscribeTo(Plan::all()->random());
        });
    }
}
