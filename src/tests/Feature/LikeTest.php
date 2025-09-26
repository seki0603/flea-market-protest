<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function いいねした商品として登録する()
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

        $this->actingAs($user)->post(route('products.like', $product->id));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertEquals(1, $product->refresh()->likes()->count());
    }

    /** @test */
    public function 追加済みのアイコンは色が変化する()
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

        $user->likes()->create(['product_id' => $product->id]);

        $response = $this->actingAs($user)->get(route('item.show', $product));

        $response->assertSee('star-filled.png');
    }

    /** @test */
    public function いいねを解除する()
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

        $user->likes()->create(['product_id' => $product->id]);

        $this->actingAs($user)->delete(route('products.unlike', $product->id));

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertEquals(0, $product->refresh()->likes()->count());
    }
}
