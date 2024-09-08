<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $faker = Faker::create();

        // Predefined arrays for specific attributes
        $carModels = ['Toyota Corolla', 'Honda Civic', 'Ford Mustang', 'Chevrolet Camaro', 'Tesla Model S', 'BMW 3 Series'];
        $favoriteFoods = ['Pizza', 'Sushi', 'Burger', 'Pasta', 'Tacos', 'Steak', 'Salad'];
        $pets = ['Dog', 'Cat', 'Parrot', 'Fish', 'Hamster', 'Rabbit'];
        $countries = ['United States', 'Canada', 'Australia', 'United Kingdom', 'Germany', 'Japan'];

        for ($i = 0; $i < 1000; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'nickname' => $faker->userName,
                'country' => $faker->randomElement($countries),
                'pet' => $faker->randomElement($pets),
                'car_model' => $faker->randomElement($carModels),
                'favorite_food' => $faker->randomElement($favoriteFoods),
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => $faker->dateTimeThisYear(),
                'password' => Hash::make('password'), // Default password for all users
                'remember_token' => $faker->md5,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
