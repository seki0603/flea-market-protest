<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

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

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        $profile = $user->profile;
        $profile->postal_code = $request->postal_code;
        $profile->address = $request->address;
        $profile->building = $request->building;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $profile->avatar_path = $path;
        }

        $profile->save();

        $user->name = $request->name;
        $user->save();

        return redirect()->route('profile.edit')->with('message', 'プロフィールを更新しました');
    }
}
