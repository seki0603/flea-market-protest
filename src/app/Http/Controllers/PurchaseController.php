<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;

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
}
