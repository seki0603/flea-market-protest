<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        // 日本語ロケールは $this->faker が内部的に保持（ja_JP を使うなら FactoryServiceProvider 側で設定 or そのままでもOK）
        $names  = ['腕時計', 'ノートパソコン', 'スニーカー', '漫画セット', 'ギター', '掃除機', 'イヤホン', '冷蔵庫', 'カメラ', 'ジャケット'];
        $brands = ['ソニー', 'パナソニック', 'シャープ', 'ユニクロ', 'ナイキ', 'アディダス', '任天堂', '富士通', '東芝', 'なし', ''];

        return [
            'seller_id'   => User::inRandomOrder()->first()->id ?? User::factory(),
            'name'        => $this->faker->randomElement($names),
            'brand_name'  => $this->faker->randomElement($brands),
            'price'       => $this->faker->numberBetween(500, 20000),
            'condition'   => $this->faker->numberBetween(0, 3), // あなたのスキーマに合わせる
            'description' => $this->faker->realText(50),
            'image_path'  => 'products/sample' . $this->faker->numberBetween(1, 5) . '.png',
        ];
    }

    /**
     * 購入済みにする（生成時に buyer_id / sold_at を確定）
     */
    public function purchasedBy(User $buyer)
    {
        return $this->state(function () use ($buyer) {
            return [
                'buyer_id' => $buyer->id,
                'sold_at'  => now(),
            ];
        })->afterCreating(function (Product $product) use ($buyer) {
            $faker = $this->faker;

            Order::create([
                'product_id'        => $product->id,
                'buyer_id'          => $buyer->id,
                'seller_id'         => $product->seller_id,
                'price'             => $product->price,
                'payment_method'    => $faker->randomElement(['コンビニ支払い', 'カード支払い']),
                'payment_status'    => 'paid',
                'ship_postal_code'  => $faker->numerify('###-####'),
                'ship_address'      => $faker->address(),
                'ship_building'     => $faker->optional()->secondaryAddress(),
                'ordered_at'        => now(),
                'paid_at'           => now(),
            ]);
        });
    }
}
