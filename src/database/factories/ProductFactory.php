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
        $names  = ['腕時計', 'ノートパソコン', 'スニーカー', '漫画セット', 'ギター', '掃除機', 'イヤホン', '冷蔵庫', 'カメラ', 'ジャケット'];
        $brands = ['ソニー', 'パナソニック', 'シャープ', 'ユニクロ', 'ナイキ', 'アディダス', '任天堂', '富士通', '東芝', 'なし', ''];
        $descriptions = [
            'ほとんど使用していないため状態は良好です。',
            '動作確認済みですが、中古品のためご理解ください。',
            '新品未使用です。即購入歓迎します。',
            '箱付きでお届けします。',
            '多少の傷がありますが、使用には問題ありません。',
        ];

        return [
            'seller_id'   => User::inRandomOrder()->first()->id ?? User::factory(),
            'name'        => $this->faker->randomElement($names),
            'brand_name'  => $this->faker->randomElement($brands),
            'price'       => $this->faker->numberBetween(500, 20000),
            'condition'   => $this->faker->numberBetween(0, 3),
            'description' => $this->faker->randomElement($descriptions),
            'image_path'  => 'products/sample' . $this->faker->numberBetween(1, 5) . '.png',
        ];
    }

    /**
     * 購入済みにする
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
