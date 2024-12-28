<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Tạo 1000 records
        for ($i = 1; $i <= 1000; $i++) {
            Category::create([
                'name' => $faker->word, // Tên ngẫu nhiên
                'description' => $faker->sentence, // Mô tả ngẫu nhiên
            ]);
        }
    }
}
