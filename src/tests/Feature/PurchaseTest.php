<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\MOdels\Order;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function 「購入する」ボタンを押下すると購入が完了する()
    {
        $this->seed();

        $user = User::first();
        $product = Product::first();

        $this->actingAs($user)
            ->post(route('purchase.store', $product->id), [
                'payment_method' => 'カード支払い',
                'ship_postal_code' => '123-4567',
                'ship_address' => '大阪市住吉区',
            ]);

        $this->assertDatabaseHas('orders', [
            'product_id' => $product->id,
            'buyer_id' => $user->id,
            'payment_method' => 'カード支払い',
            'ship_postal_code' => '123-4567',
            'ship_address' => '大阪市住吉区',
        ]);

        $this->assertNotNull($product->fresh()->sold_at);
    }

    /** @test */
    public function 購入した商品は一覧画面にて「sold」と表示()
    {
        $this->seed();

        $user = User::first();
        $product = Product::first();

        $this->actingAs($user)
            ->post(route('purchase.store', $product->id), [
                'payment_method' => 'カード支払い',
                'ship_postal_code' => '123-4567',
                'ship_address' => '大阪市住吉区',
            ]);

        $response = $this->get(route('items.index'));
        $response->assertSee('sold');
    }

    /** @test */
    public function 購入した商品一覧に追加されている()
    {
        $this->seed();

        $user = User::first();
        $product = Product::first();

        $this->actingAs($user)
            ->post(route('purchase.store', $product->id), [
                'payment_method' => 'カード支払い',
                'ship_postal_code' => '123-4567',
                'ship_address' => '大阪市住吉区',
            ]);

        $response = $this->get(route('profile.index', ['page' => 'buy']));
        $response->assertSee($product->name);
    }

    /** @test */
    public function 支払い方法の選択が小計画面に反映される()
    {
        $this->seed();

        $user = User::first();
        $product = Product::first();

        $this->actingAs($user)
            ->post(route('purchase.store', $product->id), [
                'payment_method' => 'カード支払い',
                'ship_postal_code' => '123-4567',
                'ship_address' => '大阪市住吉区',
            ]);

        // 小計画面の値がDB保存されているかで確認
        $this->assertDatabaseHas('orders', [
            'product_id' => $product->id,
            'buyer_id' => $user->id,
            'payment_method' => 'カード支払い',
        ]);
    }

    /** @test */
    public function 購入した商品に送付先住所が紐づいて登録される()
    {
        $this->seed();

        $user = User::first();
        $product = Product::first();

        $this->actingAs($user)
            ->post(route('purchase.store', $product->id), [
                'payment_method' => 'コンビニ支払い',
                'ship_postal_code' => '123-4567',
                'ship_address' => '大阪市住吉区',
                'ship_building' => 'テストビル101'
            ]);

        $this->assertDatabaseHas('orders', [
            'product_id' => $product->id,
            'ship_postal_code' => '123-4567',
            'ship_address' => '大阪市住吉区',
            'ship_building' => 'テストビル101'
        ]);
    }
}
