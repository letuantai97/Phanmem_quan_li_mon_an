<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    public function run()
    {
        $foods = [
            // Món Khai Vị
            [
                'name' => 'Gỏi Cuốn Tôm Thịt',
                'description' => 'Gỏi cuốn tươi ngon với tôm, thịt heo và rau sống',
                'price' => 55000,
                'category_id' => 1,
            ],
            [
                'name' => 'Chả Giò',
                'description' => 'Chả giò giòn rụm nhân thịt heo',
                'price' => 45000,
                'category_id' => 1,
            ],

            // Món Chính
            [
                'name' => ' Sườn Nướng',
                'description' => ' sườn nướng thơm ngon',
                'price' => 45000,
                'category_id' => 1,
                'status' => true
            ],
            [
                'name' => 'Phở Bò',
                'description' => 'Phở bò truyền thống Hà Nội',
                'price' => 55000,
                'category_id' => 1,
                'status' => true
            ],
            [
                'name' => 'Bún Bò Huế',
                'description' => 'Bún bò Huế cay nồng đặc trưng',
                'price' => 70000,
                'category_id' => 2,
            ],

            // Món Tráng Miệng
            [
                'name' => 'Chè Thái',
                'description' => 'Chè Thái thơm ngon',
                'price' => 25000,
                'category_id' => 2,
                'status' => true
            ],
            [
                'name' => 'Bánh Flan',
                'description' => 'Bánh flan mềm mịn béo ngậy',
                'price' => 25000,
                'category_id' => 3,
            ],

            // Đồ Uống
            [
                'name' => 'Trà Đào',
                'description' => 'Trà đào thơm mát',
                'price' => 35000,
                'category_id' => 4,
            ],
            [
                'name' => 'Trà Sữa Trân Châu',
                'description' => 'Trà sữa với trân châu đen',
                'price' => 35000,
                'category_id' => 3,
                'status' => true
            ],
            [
                'name' => 'Cà Phê Sữa Đá',
                'description' => 'Cà phê sữa đá thơm ngon',
                'price' => 30000,
                'category_id' => 3,
                'status' => true
            ],

            // Món Lẩu
            [
                'name' => 'Lẩu Thái',
                'description' => 'Lẩu Thái chua cay (2-3 người)',
                'price' => 299000,
                'category_id' => 4,
                'status' => true
            ],
            [
                'name' => 'Lẩu Hải Sản',
                'description' => 'Lẩu hải sản tươi ngon (2-3 người)',
                'price' => 350000,
                'category_id' => 4,
                'status' => true
            ],
            [
                'name' => 'Bò Nướng',
                'description' => 'Bò nướng tảng',
                'price' => 189000,
                'category_id' => 5,
                'status' => true
            ],
            [
                'name' => 'Hải Sản Nướng',
                'description' => 'Combo hải sản nướng',
                'price' => 259000,
                'category_id' => 5,
                'status' => true
            ],
            [
                'name' => 'Gà Nướng',
                'description' => 'Gà nướng nguyên con',
                'price' => 289000,
                'category_id' => 5,
                'status' => true
            ]
        ];

        foreach ($foods as $food) {
            Food::create($food);
        }
    }
}
