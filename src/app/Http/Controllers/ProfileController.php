<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'sell');
        $sellProducts = $user->products()->latest()->get();
        $buyProducts = $user->buyProducts()->latest()->get();

        return view('mypage.index', compact('user', 'sellProducts', 'buyProducts', 'tab'));
    }

    public function edit()
    {
        $user = auth()->user()->load('profile');

        return view('mypage.profile', compact('user'));
    }

}
