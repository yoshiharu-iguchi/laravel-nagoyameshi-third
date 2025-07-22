<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryRestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = Storage::path('category_restaurant.csv');
        $handle = fopen($file,'r');

        while (($data=fgetcsv($handle)) !== false)
        {
            Db::table('category_restaurant')->insert([
                'id' => $data[0],
                'restaurant_id' => $data[1],
                'category_id' => $data[2],
                'created_at' => $data[3],
                'updated_at' => $data[4],

            ]);
 
        }
        fclose($handle);
    }
}
