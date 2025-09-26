<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Product;
use App\Models\Category;
use App\Models\Like;
use App\Models\ProductComment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function 商品詳細ページに必要な情報が表示される()
    {
        $this->seed(\Database\Seeders\TestProductsSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);

        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $user->markEmailAsVerified();

        $profile = UserProfile::create([
            'user_id' => $user->id,
            'avatar_path' => 'avatars/test.png',
            'postal_code' => '123-4567',
            'address' => '大阪市住吉区',
        ]);

        $product = Product::first();

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
        $this->seed(\Database\Seeders\TestProductsSeeder::class);
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);

        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $user->markEmailAsVerified();

        $product = Product::first();
        $categories = Category::take(2)->get();
        $product->categories()->sync($categories->pluck('id'));

        $response = $this->get(route('item.show', $product->id));
        $response->assertStatus(200);
        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
        // 必要な情報取得（カテゴリ）のテストも兼任
    }
}
