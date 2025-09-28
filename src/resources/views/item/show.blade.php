@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('title', '商品詳細')

@section('content')
<div class="product">
    {{-- 商品画像 --}}
    <div class="product__img-wrapper">
        <img class="product__img" src="{{ asset('storage/'.$product->image_path) }}" alt="商品画像">
        @if($product->sold_at)
        <div class="sold-overlay">
            <span class="sold-text">Sold</span>
        </div>
        @endif
    </div>

    {{-- 商品情報 --}}
    <div class="product__detail">
        <h2 class="product__name">{{ $product->name }}</h2>
        <p class="product__brand">{{ $product->brand_name }}</p>
        <p class="product__price">¥{{ number_format($product->price) }} <span class="product__tax">(税込)</span></p>

        {{-- いいね --}}
        <div class="product__icons">
            <div class="product__icon-group">
                @verified
                @if ($product->isLikedBy(Auth::user()))
                <form action="{{ route('products.unlike', $product) }}" method="POST">
                    @csrf
                    @method('delete')
                    <button class="like-button" type="submit">
                        <img class="like-button__img" src="{{ asset('images/star-filled.png') }}" alt="いいね">
                    </button>
                </form>
                @else
                <form action="{{ route('products.like', $product) }}" method="POST">
                    @csrf
                    <button class="like-button" type="submit">
                        <img class="like-button__img" src="{{ asset('images/star.png') }}" alt="いいね">
                    </button>
                </form>
                @endif
                @else
                <a class="like-button" href="{{ route('login') }}">
                    <img class="like-button__img" src="{{ asset('images/star.png') }}" alt="いいね">
                </a>
                @endverified
                <p class="like-count">{{ $product->likes_count }}</p>
            </div>
            {{-- コメント --}}
            <div class="product__icon-group">
                @auth
                <div class="comment-icon">
                    <img class="comment-icon__img" src="{{ asset('images/speechbuble.png') }}" alt="コメント">
                </div>
                @else
                <div class="comment-icon">
                    <img class="comment-icon__img" src="{{ asset('images/speechbuble.png') }}" alt="コメント">
                </div>
                @endauth
                <p class="comment-count">{{ $product->comments_count }}</p>
            </div>
        </div>

        {{-- 購入ボタン --}}
        @if ($product->sold_at)
        <p class="product__sold-message">Sold Out</p>
        @else
        <div class="product__buy">
            @verified
            <a class="product__buy-button" href="{{ route('purchase.create', ['item_id' => $product->id]) }}">購入手続きへ</a>
            @else
            <a class="product__buy-button" href="{{ route('login') }}">購入手続きへ</a>
            @endverified
        </div>
        @endif

        {{-- 商品説明 --}}
        <h3 class="product__section-title">商品説明</h3>
        <p class="product__description">{{ $product->description }}</p>

        {{-- 商品カテゴリ --}}
        <h3 class="product__section-title">商品の情報</h3>
        <div class="product__category-wrapper">
            <p class="product__category-title">カテゴリ</p>
            <ul class="product__category-list">
                @foreach($product->categories as $category)
                <li class="product__category">{{ $category->name }}</li>
                @endforeach
            </ul>
        </div>
        <div class="product__status-wrapper">
            <p class="product__status-title">商品の状態</p>
            <p class="product__status">{{ $product->condition_label }}</p>
        </div>

        {{-- コメント欄 --}}
        <h3 class="product__section-title--gray">コメント ({{ $product->comments->count() }})</h3>

        @foreach($product->comments as $comment)
        <div class="product__comment">
            <img class="product__comment-img" src="{{ asset('storage/' . ($comment->user->profile->avatar_path)) }}"
                alt="">
            <p class="product__comment-user">{{ $comment->user->name}}</p>
        </div>
        <p class="product__comment-text">{{ $comment->body}}</p>
        @endforeach

        <p class="product__comment-title">商品へのコメント</p>
        @verified
        <form id="comment-form" action="{{ route('products.comments.store', $product->id) }}" method="POST" novalidate>
            @csrf
            <textarea class="product__comment-input" name="body">{{ old('body') }}</textarea>
            @error('body')
            <p class="error">{{ $message }}</p>
            @enderror
            @if (session('message'))
            <p class="success">{{ session('message') }}</p>
            @endif
            <button class="product__comment-button" type="submit">コメントを送信する</button>
            @else
            <textarea class="product__comment-input" name="body">{{ old('body') }}</textarea>
            <a class="product__comment-button" href="{{ route('login') }}">コメントを送信する</a>
            @endverified
        </form>

        {{-- エラー時画面スクロール --}}
        @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                    const el = document.getElementById('comment-form');
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth' });
                    }
                });
        </script>
        @endif
    </div>
</div>
@endsection