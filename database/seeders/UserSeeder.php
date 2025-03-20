<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Xóa dữ liệu cũ (nếu cần)
        DB::table('users')->truncate();

        // Chèn dữ liệu mẫu
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin'), // Mã hóa mật khẩu
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff',
                'email' => 'staff@example.com',
                'password' => Hash::make('12345678'),
                'role' => 'staff',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
