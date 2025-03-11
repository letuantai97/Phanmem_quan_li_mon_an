<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Món Chính', 'description' => 'Các món ăn chính'],
            ['name' => 'Món Tráng Miệng', 'description' => 'Các món tráng miệng'],
            ['name' => 'Đồ Uống', 'description' => 'Các loại thức uống'],
            ['name' => 'Món Lẩu', 'description' => 'Các món lẩu'],
            ['name' => 'Món Nướng', 'description' => 'Các món nướng']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
