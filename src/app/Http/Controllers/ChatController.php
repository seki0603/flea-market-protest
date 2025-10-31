<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();

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

        return view('chat.index', compact('tradingProducts'));
    }
}
