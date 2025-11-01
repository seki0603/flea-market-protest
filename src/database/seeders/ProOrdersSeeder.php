<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\ChatRoom;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('chat_messages')->truncate();
        DB::table('chat_rooms')->truncate();
        DB::table('orders')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = \Faker\Factory::create('ja_JP');

        $user1 = User::find(1);
        $user2 = User::find(2);

        $seller1Products = Product::where('seller_id', $user1->id)->get();
        $seller2Products = Product::where('seller_id', $user2->id)->get();

        $purchasePattern = [
            [$buyer = $user2, $products = $seller1Products->take(2)],
            [$buyer = $user1, $products = $seller2Products->take(2)],
        ];

        foreach ($purchasePattern as [$buyer, $products]) {
            foreach ($products as $product) {
                $order = Order::create([
                    'product_id'       => $product->id,
                    'buyer_id'         => $buyer->id,
                    'seller_id'        => $product->seller_id,
                    'price'            => $product->price,
                    'payment_method'   => 'カード支払い',
                    'payment_status'   => 'paid',
                    'ship_postal_code' => $faker->numerify('###-####'),
                    'ship_address'     => $faker->address(),
                    'ship_building'    => $faker->optional()->secondaryAddress(),
                    'status'           => '取引中',
                    'ordered_at'       => now(),
                    'paid_at'          => now(),
                ]);

                $product->update([
                    'buyer_id' => $buyer->id,
                    'sold_at'  => now(),
                ]);

                ChatRoom::create(['order_id' => $order->id]);
            }
        }
    }
}
