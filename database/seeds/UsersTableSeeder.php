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

        User::create([
            'email' => 'admin@test.com',
            'username' => 'admin',
            'password' => $password,
            'first_name' => 'Paulus',
            'last_name' => 'Wey',
            'telephone' => $faker->phoneNumber,
            'address' => $faker->address,

            'gender' => 'male',
            'birthday' => $faker->dateTimeBetween($startDate = '-30 years', $endDate = '-20 years'),

            'company' => $faker->company,
            'description' => $faker->sentence,
            'website' => $faker->domainName,
        ]);
        Follow::create([
            'follower_id' => 1,
            'following_id' => 1,
        ]);

        // And now let's generate a few dozen users for our app:
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'email' => $faker->email,
                'username' => $faker->userName,
                'password' => $password,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'telephone' => $faker->phoneNumber,
                'address' => $faker->address,

                'gender' => $faker->randomElement($array = array ('male', 'female')),
                'birthday' => $faker->dateTimeBetween($startDate = '-30 years', $endDate = '-20 years'),

                'company' => $faker->company,
                'description' => $faker->sentence,
                'website' => $faker->domainName,
            ]);
            Follow::create([
                'follower_id' => $i+2,
                'following_id' => $i+2,
            ]);
        }
    }
}
