<?php

namespace App\Http\Livewire;

use App\Models\ChatMessage;
use App\Http\Requests\ChatRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChatRoom extends Component
{
    use WithFileUploads;

    public $order;
    public $partner;
    public $newMessage = '';
    public $image;

    public function mount($order, $partner)
    {
        $this->order = $order;
        $this->partner = $partner;
        $this->newMessage = session('chat_draft_' . Auth::id()) ?? '';
    }

    public function updatedNewMessage($value)
    {
        session(['chat_draft_' . Auth::id() => $value]);
    }

    public function store()
    {
        $validated = $this->validate(
            (new ChatRequest())->rules(),
            (new ChatRequest())->messages()
        );

        $path = $this->image ? $this->image->store('chats', 'public') : null;

        ChatMessage::create([
            'chat_room_id' => $this->order->chatRoom->id,
            'sender_id' => Auth::id(),
            'message' => $validated['newMessage'],
            'image_path' => $path,
        ]);

        $this->reset(['newMessage', 'image']);
        session()->forget('chat_draft_' . Auth::id());

        $this->order->chatRoom->load('chatMessages.sender.profile');
    }

    public function render()
    {
        return view('livewire.chat-room');
    }
}
