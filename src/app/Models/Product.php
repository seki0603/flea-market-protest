<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'name',
        'brand_name',
        'price',
        'condition',
        'description',
        'image_path',
        'buyer_id',
        'sold_at',
    ];

    protected $casts = [
        'sold_at' => 'datetime'
    ];

    // リレーション
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy($user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    // アクセサ
    public function getConditionLabelAttribute()
    {
        $map = [
            0 => '良好',
            1 => '目立った傷や汚れなし',
            2 => 'やや傷や汚れあり',
            4 => '状態が悪い',
        ];
        return $map[$this->condition] ?? '';
    }
}
