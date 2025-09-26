<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function 「商品名」で部分一致検索ができる()
    {
        $this->seed(\Database\Seeders\TestProductsSeeder::class);

        $product = Product::first();
        $keyword = mb_substr($product->name, 0, 2);

        $response = $this->get('/?keyword=' . $keyword);

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    /** @test */
    public function 検索状態がマイリストでも保持されている()
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

        $keyword = mb_substr($product->name, 0, 2);

        // ホームで検索
        $response = $this->actingAs($user)->get('/?keyword=' . $keyword);
        $response->assertStatus(200);
        $response->assertSee($product->name);

        // マイリストに切り替え
        $response = $this->actingAs($user)->get('?tab=mylist&keyword=' . $keyword);
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('value="' . $keyword . '"', false);
    }
}
