<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;

class SellController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'seller_id' => Auth::id(),
            'name' => $request->name,
            'brand_name' => $request->brand_name,
            'price' => $request->price,
            'condition' => $request->condition,
            'description' => $request->description,
            'image_path' => $path,
        ]);

        $product->categories()->sync($request->categories);

        return redirect()->route('profile.index', ['page' => 'sell'])->with('success', '商品を出品しました');
    }
}