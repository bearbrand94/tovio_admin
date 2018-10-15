<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Follow;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's clear the users table first
        User::truncate();

        $faker = \Faker\Factory::create();

        // Let's make sure everyone has the same password and 
        // let's hash it before the loop, or else our seeder 
        // will be too slow.
        $password = Hash::make('tovio');

        $original_image_url = [
            url('storage/app/public/users/avatar1.png'),
            url('storage/app/public/users/avatar2.png'),
            url('storage/app/public/users/avatar3.png'),
            url('storage/app/public/users/avatar4.png'),
            url('storage/app/public/users/avatar5.png')
        ];

        User::create([
            'email' => 'admin@test.com',
            'username' => 'admin',
            'password' => $password,
            'first_name' => 'Paulus',
            'last_name' => 'Wey',
            'telephone' => $faker->phoneNumber,
            'address' => $faker->address,

            'gender' => '1',
            'birthday' => $faker->dateTimeBetween($startDate = '-30 years', $endDate = '-20 years'),

            'company' => $faker->company,
            'description' => $faker->sentence,
            'website' => $faker->domainName,
            'original_image_url' => $original_image_url[$faker->numberBetween(0, 4)],
        ]);
        Follow::create([
            'follower_id' => 1,
            'following_id' => 1,
        ]);

        // And now let's generate a few dozen users for our app:
        for ($i = 0; $i < 300; $i++) {
            User::create([
                'email' => $faker->email,
                'username' => $faker->unique()->userName,
                'password' => $password,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'telephone' => $faker->phoneNumber,
                'address' => $faker->address,

                'gender' => $faker->randomElement($array = array ('0', '1')),
                'birthday' => $faker->dateTimeBetween($startDate = '-30 years', $endDate = '-20 years'),

                'company' => $faker->company,
                'description' => $faker->sentence,
                'website' => $faker->domainName,
                'original_image_url' => $original_image_url[$faker->numberBetween(0, 4)],
            ]);
            Follow::create([
                'follower_id' => $i+2,
                'following_id' => $i+2,
            ]);
        }
    }
}
