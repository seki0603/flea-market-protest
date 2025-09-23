<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Product;
use App\Models\Category;
use App\Models\Like;
use App\Models\ProductComment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function 商品詳細ページに必要な情報が表示される()
    {
        $this->seed();

        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $profile = UserProfile::create([
            'user_id' => $user->id,
            'avatar_path' => 'avatars/test.png',
            'postal_code' => '123-4567',
            'address' => '大阪市住吉区',
        ]);

        $product = Product::create([
            'seller_id' => $user->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 1000,
            'condition' => 0,
            'description' => 'テスト説明文',
            'image_path' => 'products/test.png'
        ]);

        Like::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $comment = ProductComment::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'body' => 'テストコメント'
        ]);

        $response = $this->get(route('item.show', $product->id));

        $response->assertStatus(200)
            ->assertSee('/storage/' . $product->image_path)
            ->assertSee($product->name)
            ->assertSee($product->brand_name)
            ->assertSee('¥' . number_format($product->price))
            ->assertSee((string) $product->likes()->count())
            ->assertSee((string) $product->comments()->count())
            ->assertSee($product->description)
            ->assertSee($product->condition_label)
            ->assertSee('/storage/' . $profile->avatar_path)
            ->assertSee($user->name)
            ->assertSee($comment->body);
    }

    /** @test */
    public function 商品詳細ページに複数選択されたカテゴリが表示される()
    {
        $user = User::create([
            'name' => 'カテゴリユーザー',
            'email' => 'cat@example.com',
            'password' => bcrypt('password'),
        ]);

        $product = Product::create([
            'seller_id' => $user->id,
            'name' => 'カテゴリテスト商品',
            'price' => 2000,
            'condition' => 1,
            'description' => 'カテゴリ確認',
            'image_path' => 'products/cat.png'
        ]);

        // 複数カテゴリ紐づけ
        $categories = Category::take(2)->get();
        foreach ($categories as $category) {
            $product->categories()->attach($category->id);
        }

        $response = $this->get(route('item.show', $product->id));

        $response->assertStatus(200);
        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
        // 必要な情報取得（カテゴリ）のテストも兼任
    }
}
