<?php

use Illuminate\Database\Seeder;
use App\Follow;

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
        	for($j = 0; $j < $faker->numberBetween(1, 5); $j++){
	            Follow::create([
	                'follower_id' => $i,
	                'following_id' => $faker->numberBetween(1, 10),
	            ]);
        	}
        }
    }
}
