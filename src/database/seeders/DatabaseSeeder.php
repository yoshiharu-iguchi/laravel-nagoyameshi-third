<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\RestaurantSeeder;
use Database\Seeders\CategoryRestaurantSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdminSeeder::class,
            RestaurantSeeder::class,
            CategoryRestaurantSeeder::class,
        ]);
    }
}

