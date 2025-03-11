<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**y
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Chạy seeder của Category
        $this->call([
            CategorySeeder::class,
            FoodSeeder::class,
            UserSeeder::class
        ]);
    }
}
