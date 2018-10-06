<?php

use Illuminate\Database\Seeder;
use App\Follow;
use App\User;
use App\Notifications\FollowCreated;

class NetworksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Follow::truncate();

        $faker = \Faker\Factory::create();

        // create relation of like post
        for ($i = 1; $i <= 11; $i++) {
        	for($j = 0; $j < $faker->numberBetween(5, 9); $j++){
                $following_id = $faker->unique()->numberBetween(1, 10);
	            $follow = Follow::create([
	                'follower_id' => $i,
	                'following_id' => $following_id,
	            ]);

                $user_notif = User::find($following_id);
                Notification::send($user_notif, new FollowCreated($follow));
        	}
            $faker->unique($reset = true);
        }
    }
}
