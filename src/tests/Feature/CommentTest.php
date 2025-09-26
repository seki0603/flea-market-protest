<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use DatabaseMigrations;

    private function createVerifiedUserWithProfile()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $user->markEmailAsVerified();

        $user->profile()->create([
            'postal_code' => '123-4567',
            'address' => '大阪市住吉区',
            'building' => 'テストビル101',
            'avatar_path' => 'avatars/default.png',
        ]);

        return $user;
    }

    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        $this->seed(\Database\Seeders\TestProductsSeeder::class);

        $user = $this->createVerifiedUserWithProfile();
        $product = Product::first();

        $this->actingAs($user)->post(route('products.comments.store', $product->id), [
            'body' => 'テストコメント',
        ])
        ->assertRedirect();

        $this->assertDatabaseHas('product_comments', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'body' => 'テストコメント',
        ]);
    }

    /** @test */
    public function ログイン前のユーザーはコメントを送信できない()
    {
        $this->seed(\Database\Seeders\TestProductsSeeder::class);

        $product = Product::first();

        $response = $this->post(route('products.comments.store', $product->id), [
            'body' => '未ログインコメント',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('product_comments', [
            'body' => '未ログインコメント',
        ]);
    }

    /** @test */
    public function コメントが未入力の場合はバリデーションエラー()
    {
        $this->seed(\Database\Seeders\TestProductsSeeder::class);

        $user = $this->createVerifiedUserWithProfile();
        $product = Product::first();

        $response = $this->actingAs($user)->post(route('products.comments.store', $product->id), [
            'body' =>'',
        ]);

        $response->assertSessionHasErrors('body');
        $this->assertDatabaseMissing('product_comments', [
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function コメントが255字以上の場合はバリデーションメッセージ()
    {
        $this->seed(\Database\Seeders\TestProductsSeeder::class);

        $user = $this->createVerifiedUserWithProfile();
        $product = Product::first();

        $longText = str_repeat('あ', 256);

        $response = $this->actingAs($user)->post(route('products.comments.store', $product->id), [
            'body' => $longText,
        ]);

        $response->assertSessionHasErrors('body');
        $this->assertDatabaseMissing('product_comments', [
            'body' => $longText,
        ]);
    }
}
