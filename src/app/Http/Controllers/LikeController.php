<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class LikeController extends Controller
{
    public function store(Product $product)
    {
        $product->likes()->firstOrCreate([
            'user_id' => Auth::id(),
        ]);
        return back();
    }

    public function destroy(Product $product)
    {
        $product->likes()->where('user_id', Auth::id())->delete();
        return back();
    }
}
