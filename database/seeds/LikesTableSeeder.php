<?php

use Illuminate\Database\Seeder;
use App\Like;
use App\User;
use App\Notifications\LikeCreated;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Like::truncate();

        $faker = \Faker\Factory::create();

        // create relation of like post
        for ($i = 0; $i < 1500; $i++) {
            Like::create([
                'user_id' => $faker->numberBetween(1, 10),
                'table_name' => 'posts',
                'reference_id' => $faker->numberBetween(1, 500),
            ]);
        }

        // create relation of like comment
        for ($i = 0; $i < 500; $i++) {
            $user_id = $faker->numberBetween(1, 300);
            $like = Like::create([
                'user_id' => $user_id,
                'table_name' => 'comments',
                'reference_id' => $faker->numberBetween(1, 3300),
            ]);
            $user_notif = User::find($user_id);
            Notification::send($user_notif, new LikeCreated($like));
        }
    }
}
