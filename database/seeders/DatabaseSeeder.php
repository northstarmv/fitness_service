<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\Workouts;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Food::create([
                'name' => 'Pizza',
                'potion' => '1 Slice',
                'calories' => '200',
                'carbs' => '10',
                'proteins' => '5',
                'fat' => '5',
                'sat_fat' => '5',
                'fibers' => '5',
        ]);

        Food::create([
            'name' => 'Apple',
            'potion' => '1 Apple',
            'calories' => '100',
            'carbs' => '5',
            'proteins' => '3',
            'fat' => '3',
            'sat_fat' => '3',
            'fibers' => '3',
        ]);

        Food::create([
            'name' => 'Orange',
            'potion' => '1 Orange',
            'calories' => '50',
            'carbs' => '3',
            'proteins' => '2',
            'fat' => '2',
            'sat_fat' => '2',
            'fibers' => '2',
        ]);

        Workouts::create([
            'title'=> 'PushUps',
            'description'=> 'PushUps are a great exercise for your chest, triceps, and shoulders. They can also help you build strength and endurance, and they can be a great way to build your upper body.',
            'animation_url'=> 'https://gymvisual.com/img/p/2/1/4/9/4/21494.gif',
        ]);

        Workouts::create([
            'title'=> 'Planks',
            'description'=> 'Planks are a great exercise for your abs, core, and back. They can also help you build strength and endurance, and they can be a great way to build your upper body.',
            'animation_url'=> 'https://gymvisual.com/img/p/1/5/9/1/3/15913.gif',
        ]);

        Workouts::create([
            'title'=> 'Running',
            'description'=> 'Running was invented in 1784 by Thomas Running when he tried to walk twice the same time',
            'animation_url'=> 'https://gymvisual.com/img/p/1/4/7/3/6/14736.gif',
        ]);

        Workouts::create([
            'title'=> '',
            'description'=> '',
            'animation_url'=> '',
        ]);
    }
}
