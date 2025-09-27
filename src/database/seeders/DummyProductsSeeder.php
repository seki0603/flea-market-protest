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
        $bucket = collect();

        foreach ($users as $user) {
            // 出品商品：各ユーザー5〜6件
            $created = Product::factory()->count(rand(5, 6))->create(['seller_id' => $user->id]);
            $bucket  = $bucket->merge($created);

            // 購入商品：各ユーザー5〜6件
            $created = Product::factory()->count(rand(5, 6))->purchasedBy($user)->create();
            $bucket  = $bucket->merge($created);
        }

        // コメント：各商品1〜2件
        foreach ($bucket as $product) {
            $commentUsers = $users->random(rand(1, 2));
            foreach ($commentUsers as $u) {
                ProductComment::create([
                    'product_id' => $product->id,
                    'user_id'    => $u->id,
                    'body'       => $faker->realText(rand(20, 100)),
                ]);
            }
        }

        // いいね：各ユーザー5件
        foreach ($users as $u) {
            $liked = $bucket->random(min(5, $bucket->count()));
            foreach ($liked as $p) {
                Like::firstOrCreate([
                    'user_id'    => $u->id,
                    'product_id' => $p->id,
                ]);
            }
        }
    }
}
