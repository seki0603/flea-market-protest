<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->get('tab', 'sell');
        $sellProducts = $user->products()->latest()->get();
        $buyProducts = $user->buyProducts()->latest()->get();

        $tradingOrders = Order::where('status', '取引中')->where(function ($query) use ($user) {
            $query->where('buyer_id', $user->id)->orWhereHas('product', function ($query) use ($user) {
                $query->where('seller_id', $user->id);
            });
        })
        ->with(['product', 'chatRoom.chatMessages'])->get()
            ->sortByDesc(function ($order) {
                return optional($order->chatRoom?->chatMessages->last())->created_at;
            });

        $tradingProducts = $tradingOrders->pluck('product');

        return view('mypage.index', compact(
            'user',
            'sellProducts',
            'buyProducts',
            'tradingProducts',
            'tab'
        ));
    }

    public function edit()
    {
        $user = auth()->user()->load('profile');

        return view('mypage.profile', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        $wasIncomplete = empty($user->profile->postal_code) || empty($user->profile->address);

        DB::transaction(function () use ($request, $user) {
            $profileData = $request->only(['postal_code', 'address', 'building']);
            if ($request->hasFile('avatar')) {
                $profileData['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
            }
            $user->profile->update($profileData);
            $user->update(['name' => $request->name]);
        });

        if ($wasIncomplete) {
            return redirect('/?tab=mylist')->with('message', 'プロフィールを登録しました');
        }

        return redirect()->route('profile.edit')->with('message', 'プロフィールを更新しました');
    }
}
