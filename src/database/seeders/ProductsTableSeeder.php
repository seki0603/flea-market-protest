<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Like;
use App\Models\ProductComment;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('ja_JP');

        /** @var array<int,array<string,mixed>> $productData */
        $productData = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'products/Clock.jpg',
                'condition' => 0
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'products/HDD.jpg',
                'condition' => 1
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand_name' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'products/Onion.jpg',
                'condition' => 2
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand_name' => '',
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'products/Shoes.jpg',
                'condition' => 3
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand_name' => '',
                'description' => '高性能なノートパソコン',
                'image_path' => 'products/Laptop.jpg',
                'condition' => 0
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand_name' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'products/Mic.jpg',
                'condition' => 1
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand_name' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'products/Pocket.jpg',
                'condition' => 2
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand_name' => 'なし',
                'description' => '使いやすいタンブラー',
                'image_path' => 'products/Tumbler.jpg',
                'condition' => 3
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image_path' => 'products/Coffee.jpg',
                'condition' => 0
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand_name' => '',
                'description' => '便利なメイクアップセット',
                'image_path' => 'products/Makeup.jpg',
                'condition' => 1
            ],
        ];

        $createdProducts = collect();

        // 指定10件を作成（出品者はダミーユーザーに振り分け）
        foreach ($productData as $data) {
            $product = Product::create($data + ['seller_id' => rand(1, 3)]);
            $createdProducts->push($product);

            // コメント 1〜2件
            $commentUsers = User::inRandomOrder()->take(rand(1, 2))->get();
            foreach ($commentUsers as $user) {
                ProductComment::create([
                    'product_id' => $product->id,
                    'user_id'    => $user->id,
                    'body'       => $faker->realText(rand(20, 100)),
                ]);
            }
        }

        // いいね：各ユーザー 5件（指定10件から）
        $users = User::all();
        foreach ($users as $user) {
            $liked = $createdProducts->random(min(5, $createdProducts->count()));
            foreach ($liked as $product) {
                Like::firstOrCreate([
                    'user_id'    => $user->id,
                    'product_id' => $product->id,
                ]);
            }
        }

        // （任意）購入：各ユーザー 2件（指定10件から、売れてない中から抽出）
        foreach ($users as $buyer) {
            $candidates = $createdProducts->whereNull('buyer_id')->where('seller_id', '!=', $buyer->id)->values();
            if ($candidates->isEmpty()) continue;

            $toBuy = $candidates->random(min(2, $candidates->count()));
            foreach ($toBuy as $p) {
                $p->update(['buyer_id' => $buyer->id, 'sold_at' => now()]);
                // Order を合わせて作成（Factory と重複しないよう簡易作成）
                \App\Models\Order::create([
                    'product_id'        => $p->id,
                    'buyer_id'          => $buyer->id,
                    'seller_id'         => $p->seller_id,
                    'price'             => $p->price,
                    'payment_method'    => 'カード支払い',
                    'payment_status'    => 'paid',
                    'stripe_payment_intent_id' => null,
                    'ship_postal_code'  => $faker->numerify('###-####'),
                    'ship_address'      => $faker->address(),
                    'ship_building'     => $faker->optional()->secondaryAddress(),
                    'ordered_at'        => now(),
                    'paid_at'           => now(),
                ]);
            }
        }
    }
}
