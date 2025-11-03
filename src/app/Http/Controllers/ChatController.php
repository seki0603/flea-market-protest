<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index($chatRoomId = null)
    {
        $user = auth()->user();

        $tradingOrders = Order::whereIn('status', ['取引中', '取引完了待ち'])
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhereHas('product', function ($query) use ($user) {
                        $query->where('seller_id', $user->id);
                    });
            })
            ->whereDoesntHave('ratings', function ($query) use ($user) {
                $query->where('rater_id', $user->id);
            })
            ->with([
                'product',
                'product.seller.profile',
                'buyer.profile',
                'chatRoom.chatMessages.sender.profile',
            ])
            ->withCount(['chatRoom as latest_message_at' => function ($query) {
                $query->select(DB::raw('MAX(chat_messages.created_at)'))
                    ->join('chat_messages', 'chat_rooms.id', '=', 'chat_messages.chat_room_id');
            }])->orderByDesc('latest_message_at')->get();

        $currentOrder = $tradingOrders->firstWhere('chatRoom.id', $chatRoomId) ?? $tradingOrders->first();

        if (! $currentOrder) {
            return view('chat.index', [
                'tradingOrders' => collect(),
                'currentOrder' => null,
                'partner' => null,
            ]);
        }

        // 出品者または購入者のどちら側でログインしているか判定して相手ユーザーを取得
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
