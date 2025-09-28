<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use App\Models\ProductComment;
use Illuminate\Database\Seeder;

class DummyProductsSeeder extends Seeder
{
    public function run()
    {
        $faker  = \Faker\Factory::create('ja_JP');
        $users  = User::all();
        $products = collect();

        foreach ($users as $user) {
            // 出品商品：各ユーザー5〜6件
            $createdProducts = Product::factory()->count(rand(5, 6))->create(['seller_id' => $user->id]);
            $products  = $products->merge($createdProducts);

            // 購入商品：各ユーザー5〜6件
            $purchasedProducts = Product::factory()->count(rand(5, 6))->purchasedBy($user)->create();
            $products  = $products->merge($purchasedProducts);
        }

        // コメント：各商品1〜2件
        foreach ($products as $product) {
            $commentUsers = $users->random(rand(1, 2));
            foreach ($commentUsers as $commentUser) {
                ProductComment::create([
                    'product_id' => $product->id,
                    'user_id'    => $commentUser->id,
                    'body'       => $faker->realText(rand(20, 100)),
                ]);
            }
        }

        // いいね：各ユーザー5件
        foreach ($users as $user) {
            $likedProducts = $products->random(min(5, $products->count()));
            foreach ($likedProducts as $likedProduct) {
                Like::firstOrCreate([
                    'user_id'    => $user->id,
                    'product_id' => $likedProduct->id,
                ]);
            }
        }
    }
}
