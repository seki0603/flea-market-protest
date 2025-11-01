<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class ChatRoom extends Component
{
    public $order;
    public $partner;
    public $newMessage = '';

    public function mount($order, $partner)
    {
        $this->order = $order;
        $this->partner = $partner;
    }

    public function sendMessage()
    {
        if (trim($this->newMessage) === '') return;

        $this->order->chatRoom->chatMessages()->create([
            'sender_id' => Auth::id(),
            'message'   => $this->newMessage,
        ]);

        $this->newMessage = '';
        $this->order->chatRoom->load('chatMessages.sender.profile');
    }

    public function render()
    {
        return view('livewire.chat-room');
    }
}
