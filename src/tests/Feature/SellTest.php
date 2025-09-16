<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SellTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function ユーザーは商品を出品できる()
    {
        Storage::fake('public');

        $this->seed();

        $user = User::first();
        $categories = Category::take(3)->get();

        $this->actingAs($user);

        $file = UploadedFile::fake()->create('product.jpg', 100, 'image/jpeg');

        $response = $this->post(route('sell.store'), [
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => '1000',
            'condition' => 1,
            'description' => 'テスト説明文',
            'categories' => $categories->pluck('id')->toArray(),
            'image' => $file,
        ]);


        $response->assertRedirect(route('profile.index', ['page' => 'sell'], false));

        $this->assertDatabaseHas('products', [
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'price' => 1000,
            'condition' => 1,
            'description' => 'テスト説明文',
            'seller_id' => $user->id,
        ]);

        $product = Product::latest('id')->first();
        $storedIds = $product->categories()->pluck('categories.id')->toArray();
        $expectedIds = $categories->pluck('id')->toArray();

        $this->assertEqualsCanonicalizing($expectedIds, $storedIds);

        Storage::disk('public')->assertExists($product->image_path);
    }

    /** @test */
    public function 必須項目が未入力の場合はバリデーションエラー()
    {
        $this->seed();

        $user = User::first();
        $this->actingAs($user);

        $response = $this->post(route('sell.store'), [
            'name' => '',
            'price' => '',
            'condition' => '',
            'description' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'price', 'condition', 'description']);
    }
}
