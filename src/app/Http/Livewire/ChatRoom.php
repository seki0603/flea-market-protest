<?php

namespace App\Http\Livewire;

use App\Models\ChatMessage;
use App\Http\Requests\ChatRequest;
use App\Http\Requests\ChatUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChatRoom extends Component
{
    use WithFileUploads;

    public $order, $partner;
    public $newMessage = '';
    public $image;
    public $updateMessage = [];
    public $chatMessages = [];

    public function mount($order, $partner)
    {
        $this->order = $order;
        $this->partner = $partner;
        $this->newMessage = session('chat_draft_' . Auth::id()) ?? '';

        $this->order->chatRoom->chatMessages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', '未読')
            ->update(['is_read' => '既読']);
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
        $this->emit('refreshChatRoom');
    }

    public function update($id)
    {
        $validated = $this->validate(
            (new ChatUpdateRequest())->rules(),
            (new ChatUpdateRequest())->messages()
        );

        $message = ChatMessage::findOrFail($id);
        $message->update([
            'message' => $validated['updateMessage'][$id],
        ]);

        unset($this->updateMessage[$id]);
        $this->emit('refreshChatRoom');
    }

    public function delete($id)
    {
        $deleteMessage = ChatMessage::findOrFail($id);

        if ($deleteMessage->image_path) {
            Storage::disk('public')->delete($deleteMessage->image_path);
        }

        $deleteMessage->delete();

        $this->order->chatRoom->load('chatMessages.sender.profile');
        $this->emit('refreshChatRoom');
    }

    public function render()
    {
        $this->chatMessages = $this->order->chatRoom
            ->chatMessages()
            ->with('sender.profile')
            ->oldest()
            ->get();

        foreach ($this->chatMessages as $msg) {
            if (! isset($this->updateMessage[$msg->id])) {
                $this->updateMessage[$msg->id] = $msg->message;
            }
        }

        return view('livewire.chat-room', [
            'chatMessages' => $this->chatMessages,
        ]);
    }
}
