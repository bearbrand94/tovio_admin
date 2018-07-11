<?php

use Illuminate\Database\Seeder;
use App\Post;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Post::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 500; $i++) {
            Post::create([
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'posted_by' => $faker->numberBetween(1, 11),
                'schedule_date' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
                // 'is_completed' => $faker->boolean,
            ]);
        }
    }
}
