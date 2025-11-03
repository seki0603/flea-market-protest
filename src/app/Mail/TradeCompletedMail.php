<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TradeCompletedMail extends Mailable
{
    public $order;
    public $partner;

    public function __construct($order, $partner)
    {
        $this->order = $order;
        $this->partner = $partner;
    }

    public function build()
    {
        return $this->subject('取引完了のお知らせ')
            ->markdown('emails.trade_completed');
    }
}
