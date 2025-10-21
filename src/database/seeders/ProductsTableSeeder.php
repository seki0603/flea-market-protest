<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Like;
use App\Models\ProductComment;
use App\Models\Order;

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
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'condition' => 0
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'condition' => 1
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand_name' => 'なし',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'condition' => 2
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand_name' => '',
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'condition' => 3
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand_name' => '',
                'description' => '高性能なノートパソコン',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'condition' => 0
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand_name' => 'なし',
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'condition' => 1
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand_name' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'condition' => 2
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand_name' => 'なし',
                'description' => '使いやすいタンブラー',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'condition' => 3
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'condition' => 0
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand_name' => '',
                'description' => '便利なメイクアップセット',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'condition' => 1
            ],
        ];

        $createdProducts = collect();

        // 出品者をダミーユーザーに振り分け
        foreach ($productData as $data) {
            $product = Product::create($data + ['seller_id' => rand(1, 3)]);
            $createdProducts->push($product);

            // コメント:各商品1〜2件
            $commentUsers = User::inRandomOrder()->take(rand(1, 2))->get();
            foreach ($commentUsers as $user) {
                ProductComment::create([
                    'product_id' => $product->id,
                    'user_id'    => $user->id,
                    'body'       => $faker->realText(rand(20, 100)),
                ]);
            }
        }

        // いいね：各ユーザー 5件
        $users = User::all();
        foreach ($users as $user) {
            $likedProducts = $createdProducts->random(min(5, $createdProducts->count()));
            foreach ($likedProducts as $product) {
                Like::firstOrCreate([
                    'user_id'    => $user->id,
                    'product_id' => $product->id,
                ]);
            }
        }

        // 購入：各ユーザー 2件
        foreach ($users as $buyer) {
            $candidates = $createdProducts->whereNull('buyer_id')->where('seller_id', '!=', $buyer->id)->values();
            if ($candidates->isEmpty()) continue;

            $toBuy = $candidates->random(min(2, $candidates->count()));
            foreach ($toBuy as $product) {
                $product->update([
                    'buyer_id' => $buyer->id,
                    'sold_at' => now()
                ]);
                // Order を合わせて作成
                Order::create([
                    'product_id' => $product->id,
                    'buyer_id' => $buyer->id,
                    'seller_id' => $product->seller_id,
                    'price' => $product->price,
                    'payment_method' => 'カード支払い',
                    'payment_status' => 'paid',
                    'ship_postal_code' => $faker->numerify('###-####'),
                    'ship_address' => $faker->address(),
                    'ship_building' => $faker->optional()->secondaryAddress(),
                    'ordered_at' => now(),
                    'paid_at' => now(),
                ]);
            }
        }
    }
}
