<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MylistTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function いいねした商品だけが表示される()
    {
        $this->seed(\Database\Seeders\TestProductsSeeder::class);

        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $user->markEmailAsVerified();

        $product = Product::first();

        Like::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    /** @test */
    public function 購入済み商品は「Sold」と表示()
    {
        $this->seed(\Database\Seeders\TestProductsSeeder::class);

        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $user->markEmailAsVerified();

        $product = Product::first();

        Like::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
        $product->update([
            'buyer_id' => $user->id,
            'sold_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee('Sold');
    }

    /** @test */
    public function 未認証の場合は何も表示されない()
    {
        $this->seed(\Database\Seeders\TestProductsSeeder::class);

        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $user->markEmailAsVerified();

        $product = Product::first();

        Like::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $products = $response->viewData('products');

        $this->assertCount(0, $products);
    }
}
