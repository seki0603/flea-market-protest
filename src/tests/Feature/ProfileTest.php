<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function マイページで出品商品が表示される()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'avatar_path' => 'avatars/test.png',
            'postal_code' => '123-4567',
            'address' => '大阪市住吉区',
        ]);

        $exhibitionProduct = Product::create([
            'seller_id' => $user->id,
            'name' => '出品商品',
            'price' => '1000',
            'condition' => 0,
            'description' => '出品商品の説明文'
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200)
            ->assertSee('テストユーザー')
            ->assertSee('出品商品');
    }

    /** @test */
    public function マイページで購入商品が表示される()
    {
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'avatar_path' => 'avatars/test.png',
            'postal_code' => '123-4567',
            'address' => '大阪市住吉区',
        ]);

        $otherUser = User::create([
            'name' => '出品者ユーザー',
            'email' => 'other@example.com',
            'password' => bcrypt('password')
        ]);

        $purchaseProduct = Product::create([
            'seller_id' => $otherUser->id,
            'buyer_id' => $user->id,
            'name' => '購入商品',
            'price' => '2000',
            'condition' => 1,
            'description' => '購入商品の説明文'
        ]);

        $response = $this->actingAs($user)->get('/mypage?tab=buy');

        $response->assertStatus(200)
            ->assertSee('購入商品');
    }

    /** @test */
    public function 変更項目が初期値として過去設定されている()
    {
        $user = User::create([
            'name' => '初期ユーザー',
            'email' => 'init@example.com',
            'password' => bcrypt('password')
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'avatar_path' => 'avatars/init.png',
            'postal_code' => '987-6543',
            'address' => '東京都目黒区',
        ]);

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200)
            ->assertSee('avatars/init.png')
            ->assertSee('初期ユーザー')
            ->assertSee('987-6543')
            ->assertSee('東京都目黒区');
        // ユーザー情報取得（プロフィール画像・ユーザー名）のテストも兼任
    }
}
