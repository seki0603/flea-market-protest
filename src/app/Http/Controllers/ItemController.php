<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('tab') === 'mylist') {
            $products = Product::whereHas('likes', function ($q) {
                $q->where('user_id', Auth::id());
            })->get();
        } else {
            $products = Product::where('seller_id', '!=', Auth::id())->get();
        }
        return view('index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with(['seller', 'buyer', 'categories', 'comments', 'comments.user'])->findOrFail($id);
        return view('item.show', compact('product'));
    }
}
