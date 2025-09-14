<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductComment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function ログイン済みのユーザーはコメントを送信できる()
    {
        $this->seed();

        $user = User::first();
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
        $this->seed();

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
    public function コメントが入力されていない場合はバリデーションメッセージ()
    {
        $this->seed();

        $user = User::first();
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
        $this->seed();

        $user = User::first();
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
