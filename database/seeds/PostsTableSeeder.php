<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\User;


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
            url('storage/app/public/posts/photo1.png'),
            url('storage/app/public/posts/photo2.png'),
            url('storage/app/public/posts/photo3.jpg'),
            url('storage/app/public/posts/photo4.jpg')
        ];
        
        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 500; $i++) {
            $user_id = $faker->numberBetween(1, 300);
            $post = Post::create([
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'posted_by' => $user_id,
                'schedule_date' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 years'),
                'is_completed' => $faker->boolean($chanceOfGettingTrue = 50),
                'post_type' => $faker->randomElement($array = array ('0', '1')),
                'original_image_url' => $original_image_url[$faker->numberBetween(0, 3)],
            ]);
            // $user = User::find($user_id);
            // Notification::send($user, new PostCreated($post));
        }
    }
}
