<?php

use Illuminate\Database\Seeder;
use App\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Comment::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 80; $i++) {
            Comment::create([
            	'post_id' => $faker->numberBetween(1, 50),
                'content' => $faker->sentence,
                'commented_by' => $faker->numberBetween(1, 11),
                'comment_date' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
            ]);
        }
    }
}
