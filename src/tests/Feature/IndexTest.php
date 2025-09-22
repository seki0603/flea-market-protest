<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function 全商品を取得できる()
    {
        $this->seed();

        $response = $this->get('/');

        $expectedCount = Product::count();

        Product::all()->each(function ($product) use ($response) {
            $response->assertSee($product->name);
        });

        $this->assertEquals($expectedCount, Product::count());
    }

    /** @test */
    public function 購入済み商品は「Sold」と表示される()
    {
        $this->seed();

        $soldProduct = Product::first();
        $soldProduct->update(['buyer_id' => 1]);

        $response = $this->get('/');

        $response->assertSee('Sold');
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $this->seed();

        $userWithProduct = User::whereHas('products')->first();
        $this->assertNotNull($userWithProduct, 'Seederで商品を持つユーザーが見つかりません');

        $ownProduct = $userWithProduct->products()->first();
        $this->assertNotNull($ownProduct, 'ユーザーに紐づく商品が存在しません');

        $response = $this->actingAs($userWithProduct)->get('/');

        $response->assertDontSee($ownProduct->name);
    }
}
