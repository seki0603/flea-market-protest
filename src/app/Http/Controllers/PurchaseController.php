<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function create($item_id)
    {
        $product = Product::findOrFail($item_id);
        $user = Auth::user();

        return view('purchase.index', compact('product', 'user'));
    }

    public function address($item_id)
    {
        $product = Product::findOrFail($item_id);
        $user = Auth::user();

        return view('purchase.address', compact('product', 'user'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        session([
            'ship_postal_code' => $request->ship_postal_code,
            'ship_address' => $request->ship_address,
            'ship_building' => $request->ship_building
        ]);

        return redirect()->route('purchase.create', $item_id)->with('message', '配送先を変更しました');
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $product = Product::findOrFail($item_id);

        if ($product->sold_at) {
            return redirect()->route('items.index');
        }

        DB::transaction(function () use ($request, $product) {
            Order::create([
                'product_id' => $product->id,
                'buyer_id' => Auth::id(),
                'seller_id' => $product->seller_id,
                'price' => $product->price,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'ship_postal_code' => $request->ship_postal_code,
                'ship_address' => $request->ship_address,
                'ship_building' => $request->ship_building,
                'ordered_at' => now(),
            ]);

            $product->update([
                'buyer_id' => Auth::id(),
                'sold_at' => now(),
            ]);
        });

        //テスト用
        if (app()->environment('testing')) {
            return redirect()->route('items.index');
        }

        //本番用
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => $request->payment_method === 'カード支払い'
                ? ['card']
                : ['konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('items.index') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('purchase.create', $product->id),
        ]);

        session()->flash('message', '購入が完了しました');

        return redirect($session->url);
    }
}
