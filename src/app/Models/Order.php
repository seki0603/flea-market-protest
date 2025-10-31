<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'buyer_id',
        'seller_id',
        'price',
        'payment_method',
        'payment_status',
        'ship_postal_code',
        'ship_address',
        'ship_building',
        'status',
        'ordered_at',
        'paid_at'
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    // リレーション
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function chatRoom()
    {
        return $this->hasOne(ChatRoom::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
