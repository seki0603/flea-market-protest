<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductComment;
use Faker\Factory as Faker;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var \Faker\Generator $faker */
        $faker = Faker::create('ja_JP');

        $products = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'products/Clock.jpg',
                'condition' => 0,
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'products/HDD.jpg',
                'condition' => 1,
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand_name' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'products/Onion.jpg',
                'condition' => 2,
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand_name' => '',
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'products/Shoes.jpg',
                'condition' => 3,
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand_name' => '',
                'description' => '高性能なノートパソコン',
                'image_path' => 'products/Laptop.jpg',
                'condition' => 0,
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand_name' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'products/Mic.jpg',
                'condition' => 1,
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand_name' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'products/Pocket.jpg',
                'condition' => 2,
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand_name' => 'なし',
                'description' => '使いやすいタンブラー',
                'image_path' => 'products/Tumbler.jpg',
                'condition' => 3,
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image_path' => 'products/Coffee.jpg',
                'condition' => 0,
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand_name' => '',
                'description' => '便利なメイクアップセット',
                'image_path' => 'products/Makeup.jpg',
                'condition' => 1,
            ],
        ];

        foreach ($products as $data) {
            /** @var \App\Models\Product $product */
            $product = Product::create(array_merge($data, [
                'seller_id' => rand(1, 3),
            ]));

            $users = User::inRandomOrder()->take(rand(1, 3))->get();

            foreach ($users as $user) {
                ProductComment::create([
                    'product_id' => $product->id,
                    'user_id'    => $user->id,
                    'body'       => $faker->realText(rand(20, 100)),
                ]);
            }
        }
    }
}
