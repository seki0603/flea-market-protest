<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
}
