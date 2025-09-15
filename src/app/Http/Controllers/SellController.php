<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class SellController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('sell', compact('categories'));
    }
}
