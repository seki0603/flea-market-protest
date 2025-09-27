@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage">
    <div class="mypage__profile">
        <div class="profile__inner">
            <img class="profile__img" src="{{ asset('storage/' . $user->profile->avatar_path) }}" alt="プロフィール画像">
            <h2 class="profile__name">{{ $user->name }}</h2>
        </div>
        <a class="profile__button" href="{{ route('profile.edit') }}">プロフィールを編集</a>
    </div>
    @if (session('message'))
    <p class="success">{{ session('message') }}</p>
    @endif
</div>

<div class="tab__wrapper">
    <div class="tab">
        <a class="tab__link {{ $tab === 'sell' ? 'tab__link--active' : '' }}" href="?tab=sell">出品した商品</a>
        <a class="tab__link {{ $tab === 'buy' ? 'tab__link--active' : '' }}" href="?tab=buy">購入した商品</a>
    </div>
</div>

@if ($tab === 'sell')
<div class="products">
    @foreach($sellProducts as $product)
    <div class="product">
        <div class="product__img-wrapper">
            <img class="product__img" src="{{ asset('storage/'.$product->image_path) }}" alt="商品画像">
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
        <img class="product__img" src="{{ asset('storage/'.$product->image_path) }}" alt="商品画像">
        <p class="product__name">{{ $product->name }}</p>
    </div>
    @endforeach
</div>
@endif
@endsection