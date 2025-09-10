<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->tab === 'mylist') {
            $products = Auth::user()->products;
        } else {
            $products = Product::all();
        }
        return view('index', compact('products'));
    }
}
