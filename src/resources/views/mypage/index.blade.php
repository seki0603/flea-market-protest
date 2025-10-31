@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage">
    <h1 class="visually-hidden">プロフィール編集画面</h1>
    <div class="mypage__profile">
        <div class="profile__inner">
            <img class="profile__img" src="{{ asset('storage/' . $user->profile->avatar_path) }}" alt="">
            <p class="profile__name">{{ $user->name }}</p>
        </div>
        <a class="profile__button" href="{{ route('profile.edit') }}">プロフィールを編集</a>
    </div>
    @if (session('message'))
    <p class="success">{{ session('message') }}</p>
    @endif
</div>

{{-- タブ切り替え --}}
<div class="tab__wrapper">
    <div class="tab">
        <a class="tab__link {{ $tab === 'sell' ? 'tab__link--active' : '' }}" href="?tab=sell">出品した商品</a>
        <a class="tab__link {{ $tab === 'buy' ? 'tab__link--active' : '' }}" href="?tab=buy">購入した商品</a>
        <a class="tab__link {{ $tab === 'trading' ? 'tab__link--active' : '' }}" href="?tab=trading">取引中の商品</a>
    </div>
</div>

@if ($tab === 'sell')
<div class="products">
    @foreach($sellProducts as $product)
    <div class="product">
        <div class="product__img-wrapper">
            <img class="product__img" src="{{ \Illuminate\Support\Str::startsWith($product->image_path, 'http')
    ? $product->image_path : asset('storage/'.$product->image_path) }}" alt="商品画像">
            {{-- Sold判定 --}}
            @if($product->sold_at)
            <div class="sold-overlay">
                <span class="sold-text">Sold</span>
            </div>
            @endif
        </div>
        <p class="product__name">{{ $product->name }}</p>
    </div>
    @endforeach
</div>
@endif

@if ($tab === 'buy')
<div class="products">
    @foreach($buyProducts as $product)
    <div class="product">
        <img class="product__img" src="{{ \Illuminate\Support\Str::startsWith($product->image_path, 'http')
    ? $product->image_path : asset('storage/'.$product->image_path) }}" alt="商品画像">
        <p class="product__name">{{ $product->name }}</p>
    </div>
    @endforeach
</div>
@endif

@if ($tab === 'trading')
<div class="products">
    @foreach($tradingProducts as $product)
    <div class="product">
        <a href="{{ route('chat.index', $product->order->chatRoom->id ?? '#') }}">
            <img class="product__img" src="{{ \Illuminate\Support\Str::startsWith($product->image_path, 'http') ? $product->image_path : asset('storage/'.$product->image_path) }}" alt="商品画像">
        </a>
        <p class="product__name">{{ $product->name }}</p>
    </div>
    @endforeach
</div>
@endif
@endsection