<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LikeFeatureTest extends TestCase
{
    use DatabaseMigrations;

    public function testStoreLike()
    {
        $this->seed();

        $user = User::first();
        $product = Product::first();

        $this->actingAs($user)->post(route('products.like', $product));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertEquals(1, $product->refresh()->likes()->count());
    }

    public function testChangeIcon()
    {
        $this->seed();

        $user = User::first();
        $product = Product::first();
        $user->likes()->create(['product_id' => $product->id]);

        $response = $this->actingAs($user)->get(route('item.show', $product));

        $response->assertSee('star-filled.png');
    }

    public function testDestroyLike()
    {
        $this->seed();

        $user = User::first();
        $product = Product::first();
        $user->likes()->create(['product_id' => $product->id]);

        $this->actingAs($user)->delete(route('products.unlike', $product));

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertEquals(0, $product->refresh()->likes()->count());
    }
}
