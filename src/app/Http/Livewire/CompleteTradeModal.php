<?php

namespace App\Http\Livewire;

use App\Models\Rating;
use App\Models\UserProfile;
use App\Http\Requests\TradeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TradeCompletedMail;
use Livewire\Component;

class CompleteTradeModal extends Component
{
    public $isOpen = false;
    public $order;
    public $partner;
    public $rating;
    public $isSeller = false;
    public $hasRated = false;
    public $score;

    protected $listeners = ['openCompleteModal' => 'open'];

    public function mount($order, $partner)
    {
        $this->order = $order;
        $this->partner = $partner;
        $this->isSeller = $this->order->seller_id === auth()->id();

        $this->hasRated = Rating::where('order_id', $order->id)
            ->where('rater_id', Auth::id())
            ->exists();

        if ($this->order->status === '取引完了待ち' && ! $this->hasRated) {
            $this->isOpen = true;
        }
    }

    public function open()
    {
        $this->isOpen = true;

        if (! $this->isSeller && $this->order->status === '取引中') {
            $this->order->update(['status' => '取引完了待ち']);
            Mail::to($this->partner->email)->send(
                new TradeCompletedMail($this->order, $this->partner)
            );
        }
    }

    public function store()
    {
        $validated = $this->validate(
            (new TradeRequest())->rules(),
            (new TradeRequest())->messages()
        );

        DB::transaction(function () use ($validated) {
            // すでに評価済みなら再登録しない
            if (Rating::where('order_id', $this->order->id)
                ->where('rater_id', Auth::id())
                ->exists()
            ) {
                return;
            }

            Rating::create([
                'order_id' => $this->order->id,
                'rater_id' => Auth::id(),
                'ratee_id' => $this->partner->id,
                'score' => $validated['score'],
            ]);

            $average = Rating::where('ratee_id', $this->partner->id)->avg('score');
            UserProfile::where('user_id', $this->partner->id)->update(['average_rating' => round($average, 1)]);

            // 両者が評価済みならstatusを取引完了へ
            $buyerRated = Rating::where('order_id', $this->order->id)
                ->where('rater_id', $this->order->buyer_id)
                ->exists();
            $sellerRated = Rating::where('order_id', $this->order->id)
                ->where('rater_id', $this->order->product->seller_id)
                ->exists();

            if ($buyerRated && $sellerRated) {
                $this->order->update(['status' => '取引完了']);
            }
        });

        $this->isOpen = false;
        $this->hasRated = true;

        return redirect()->route('items.index');
    }

    public function render()
    {
        return view('livewire.complete-trade-modal');
    }
}
