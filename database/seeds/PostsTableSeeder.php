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

        $original_image_url = [
            'storage/app/public/posts/photo1.png',
            'storage/app/public/posts/photo2.png',
            'storage/app/public/posts/photo3.jpg',
            'storage/app/public/posts/photo4.jpg'
        ];
        
        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 500; $i++) {
            Post::create([
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'posted_by' => $faker->numberBetween(1, 11),
                'schedule_date' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 years'),
                'is_completed' => $faker->boolean($chanceOfGettingTrue = 50),
                'original_image_url' => $original_image_url[$faker->numberBetween(0, 3)],
            ]);
        }
    }
}
