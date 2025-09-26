<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\User;

class TestProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => '出品ユーザー',
            'email' => 'seller@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        Product::create([
            'name' => '腕時計',
            'price' => 15000,
            'brand_name' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'image_path' => 'products/Clock.jpg',
            'condition' => 0,
            'seller_id' => $user->id,
        ]);

        Product::create([
            'name' => 'HDD',
            'price' => 5000,
            'brand_name' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'image_path' => 'products/HDD.jpg',
            'condition' => 1,
            'seller_id' => $user->id,
        ]);

        Product::create([
            'name' => '玉ねぎ3束',
            'price' => 300,
            'brand_name' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'image_path' => 'products/Onion.jpg',
            'condition' => 2,
            'seller_id' => $user->id,
        ]);

        Product::create([
            'name' => '革靴',
            'price' => 4000,
            'brand_name' => '',
            'description' => 'クラシックなデザインの革靴',
            'image_path' => 'products/Shoes.jpg',
            'condition' => 3,
            'seller_id' => $user->id,
        ]);

        Product::create([
            'name' => 'ノートPC',
            'price' => 45000,
            'brand_name' => '',
            'description' => '高性能なノートパソコン',
            'image_path' => 'products/Laptop.jpg',
            'condition' => 0,
            'seller_id' => $user->id,
        ]);

        Product::create([
            'name' => 'マイク',
            'price' => 8000,
            'brand_name' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'image_path' => 'products/Mic.jpg',
            'condition' => 1,
            'seller_id' => $user->id,
        ]);

        Product::create([
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'brand_name' => '',
            'description' => 'おしゃれなショルダーバッグ',
            'image_path' => 'products/Pocket.jpg',
            'condition' => 2,
            'seller_id' => $user->id,
        ]);

        Product::create([
            'name' => 'タンブラー',
            'price' => 500,
            'brand_name' => 'なし',
            'description' => '使いやすいタンブラー',
            'image_path' => 'products/Tumbler.jpg',
            'condition' => 3,
            'seller_id' => $user->id,
        ]);

        Product::create([
            'name' => 'コーヒーミル',
            'price' => 4000,
            'brand_name' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'image_path' => 'products/Coffee.jpg',
            'condition' => 0,
            'seller_id' => $user->id,
        ]);

        Product::create([
            'name' => 'メイクセット',
            'price' => 2500,
            'brand_name' => '',
            'description' => '便利なメイクアップセット',
            'image_path' => 'products/Makeup.jpg',
            'condition' => 1,
            'seller_id' => $user->id,
        ]);
    }
}
