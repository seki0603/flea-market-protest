<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function 送付先住所変更画面で登録した住所が購入画面に反映される()
    {
        $this->seed();

        $user = User::first();
        $product = Product::first();

        $this->actingAs($user)
            ->post(route('purchase.address.update', $product->id), [
                'ship_postal_code' => '123-4567',
                'ship_address' => '大阪市住吉区',
                'ship_building' => 'テストビル101',
            ])
            ->assertRedirect(route('purchase.create', $product->id));

            $this->actingAs($user)
                ->get(route('purchase.create', $product->id))
                ->assertSee('123-4567')
                ->assertSee('大阪市住吉区')
                ->assertSee('テストビル101');
    }
}
