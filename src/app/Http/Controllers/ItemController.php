<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // キーワード検索
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // タブがマイリストの場合
        if ($request->query('tab') === 'mylist') {
            if (Auth::check()) {
                $query->whereHas('likes', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            } else {
                $query->whereRaw('0 = 1');
            }
        } else {
            if (Auth::check()) {
                $query->where('seller_id', '!=', Auth::id());
            }
        }

        $products = $query->get();

        return view('index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with(['seller', 'buyer', 'categories', 'comments', 'comments.user'])->findOrFail($id);
        return view('item.show', compact('product'));
    }
}
