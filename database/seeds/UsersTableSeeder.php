<?php

use Illuminate\Database\Seeder;
use App\User;

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
        ]);

        // And now let's generate a few dozen users for our app:
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'email' => $faker->email,
                'username' => $faker->name,
                'password' => $password,
                'first_name' => $faker->name,
                'last_name' => $faker->name,
                'telephone' => $faker->phoneNumber,
                'address' => $faker->address,
            ]);
        }
    }
}
