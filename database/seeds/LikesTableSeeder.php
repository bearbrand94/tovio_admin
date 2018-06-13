<?php

use Illuminate\Database\Seeder;
use App\Like;

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
        for ($i = 0; $i < 100; $i++) {
            Like::create([
                'user_id' => $faker->numberBetween(1, 10),
                'table_name' => 'posts',
                'reference_id' => $faker->numberBetween(1, 50),
            ]);
        }

        // create relation of like comment
        for ($i = 0; $i < 50; $i++) {
            Like::create([
                'user_id' => $faker->numberBetween(1, 10),
                'table_name' => 'comments',
                'reference_id' => $faker->numberBetween(1, 80),
            ]);
        }
    }
}
