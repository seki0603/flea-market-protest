<?php

namespace App\Http\Controllers;

use App\Models\Order;

class ChatController extends Controller
{
    public function index($chatRoomId = null)
    {
        $user = auth()->user();

        $tradingOrders = Order::where('status', '取引中')
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhereHas('product', fn($q) => $q->where('seller_id', $user->id));
            })
            ->with([
                'product',
                'product.seller.profile',
                'buyer.profile',
                'chatRoom.chatMessages.sender.profile',
            ])
            ->get()
            ->sortByDesc(fn($order) => optional($order->chatRoom?->chatMessages->last())->created_at);

        // 現在表示するチャットルーム
        $currentOrder = $tradingOrders->firstWhere('chatRoom.id', $chatRoomId) ?? $tradingOrders->first();

        // 取引相手
        $partner = $currentOrder->buyer_id === $user->id
            ? $currentOrder->product->seller
            : $currentOrder->buyer;

        return view('chat.index', [
            'tradingOrders' => $tradingOrders,
            'currentOrder' => $currentOrder,
            'partner' => $partner,
        ]);
    }
}
