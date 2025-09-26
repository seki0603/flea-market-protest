<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function マイページで出品商品が表示される()
    {
        $this->seed(\Database\Seeders\TestProductsSeeder::class);

        $user = User::where('email', 'seller@example.com')->first();
        $user->markEmailAsVerified();

        UserProfile::create([
            'user_id' => $user->id,
            'avatar_path' => 'avatars/test.png',
            'postal_code' => '123-4567',
            'address' => '大阪市住吉区',
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee(Product::first()->name);
    }

    /** @test */
    public function マイページで購入商品が表示される()
    {
        $this->seed(\Database\Seeders\TestProductsSeeder::class);

        $buyer = User::create([
            'name' => '購入ユーザー',
            'email' => 'buyer@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $buyer->markEmailAsVerified();

        UserProfile::create([
            'user_id' => $buyer->id,
            'avatar_path' => 'avatars/test.png',
            'postal_code' => '123-4567',
            'address' => '大阪市住吉区',
        ]);

        $product = Product::first();
        $product->update(['buyer_id' => $buyer->id]);

        $response = $this->actingAs($buyer)->get('/mypage?tab=buy');

        $response->assertStatus(200)
            ->assertSee($product->name);
    }

    /** @test */
    public function 変更項目が初期値として過去設定されている()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $user->markEmailAsVerified();

        UserProfile::create([
            'user_id' => $user->id,
            'avatar_path' => 'avatars/test.png',
            'postal_code' => '123-4567',
            'address' => '大阪市住吉区',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200)
            ->assertSee($user->avatar_path)
            ->assertSee($user->name)
            ->assertSee($user->postal_code)
            ->assertSee($user->address);
        // ユーザー情報取得（プロフィール画像・ユーザー名）のテストも兼任
    }
}
