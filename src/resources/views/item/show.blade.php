@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('title', '商品詳細')

@section('content')
<div class="item">
    {{-- 商品画像 --}}
    <div class="item__img-wrapper">
        <img class="item__img" src="{{ asset('storage/'.$product->image_path) }}" alt="商品画像">
        @if($product->sold_at)
        <div class="sold-overlay">
            <span class="sold-text">Sold</span>
        </div>
        @endif
    </div>

    {{-- 商品詳細情報 --}}
    <div class="item__detail">
        <h2 class="item__name">{{ $product->name }}</h2>
        <p class="item__brand">{{ $product->brand_name }}</p>
        <p class="item__price">¥{{ number_format($product->price) }} <span class="item__tax">(税込)</span></p>

        {{-- いいね --}}
        <div class="item__icons">
            <div class="item__icons-inner">
                @auth
                @if ($product->isLikedBy(Auth::user()))
                <form action="{{ route('products.unlike', $product) }}" method="POST">
                    @csrf
                    @method('delete')
                    <button class="like-btn" type="submit">
                        <img class="like-btn__img" src="{{ asset('images/star-filled.png') }}" alt="いいね">
                    </button>
                </form>
                @else
                <form action="{{ route('products.like', $product) }}" method="POST">
                    @csrf
                    <button class="like-btn" type="submit">
                        <img class="like-btn__img" src="{{ asset('images/star.png') }}" alt="いいね">
                    </button>
                </form>
                @endif
                @else
                <a class="like-btn" href="{{ route('login') }}">
                    <img class="like-btn__img" src="{{ asset('images/star.png') }}" alt="いいね">
                </a>
                @endauth
                <p class="like-count">{{ $product->likes->count() }}</p>
            </div>
            {{-- コメント --}}
            <div class="item__icons-inner">
                @auth
                <form action="" method="POST">
                    @csrf
                    <button class="comment-btn" type="submit">
                        <img class="comment-btn__img" src="{{ asset('images/speechbuble.png') }}" alt="コメント">
                    </button>
                </form>
                @else
                <a class="comment-btn" href="{{ route('login') }}">
                    <img class="comment-btn__img" src="{{ asset('images/speechbuble.png') }}" alt="コメント">
                </a>
                @endauth
                <p class="comment-count">{{ $product->comments->count() }}</p>
            </div>
        </div>

        {{-- 購入ボタン --}}
        @if ($product->sold_at)
        <p class="item__sold-message">Sold Out</p>
        @else
        <div class="item__buy">
            @auth
            <a class="item__buy-btn" href="{{ route('purchase.create', ['item_id' => $product->id]) }}">購入手続きへ</a>
            @else
            <a class="item__buy-btn" href="{{ route('login') }}">購入手続きへ</a>
            @endauth
        </div>
        @endif

        {{-- 商品説明 --}}
        <div class="item__section">
            <h3 class="item__section-ttl">商品説明</h3>
            <p class="item__description">{{ $product->description }}</p>
        </div>

        {{-- 商品カテゴリ --}}
        <h3 class="item__section-ttl">商品の情報</h3>
        <div class="item__category-wrap">
            <p class="item__category-ttl">カテゴリ</p>
            <ul class="item__category-list">
                @foreach($product->categories as $category)
                <li class="item__category">{{ $category->name }}</li>
                @endforeach
            </ul>
        </div>
        <div class="item__status-wrap">
            <p class="item__status-ttl">商品の状態</p>
            <p class="item__status">{{ $product->condition_label }}</p>
        </div>

        {{-- コメント欄 --}}
        <h3 class="item__section-ttl--gray">コメント ({{ $product->comments->count() }})</h3>

        @foreach($product->comments as $comment)
        <div class="item__comment">
            <img class="item__comment-img" src="{{ asset('storage/' . ($comment->user->profile->avatar_path)) }}" alt="">
            <p class="item__comment-user">{{ $comment->user->name}}</p>
        </div>
        <p class="item__comment-text">{{ $comment->body}}</p>
        @endforeach

        <p class="item__comment-ttl">商品へのコメント</p>
        @auth
        <form id="comment-form" class="item__comment-form" action="{{ route('products.comments.store', $product->id) }}"
            method="POST">
            @csrf
            <textarea class="item__comment-input" name="body">{{ old('body') }}</textarea>
            @error('body')
            <p class="error">{{ $message }}</p>
            @enderror
            @if (session('message'))
            <p class="success">{{ session('message') }}</p>
            @endif
            <button class="item__comment-btn" type="submit">コメントを送信する</button>
            @else
            <textarea class="item__comment-input" name="body">{{ old('body') }}</textarea>
            <a class="item__comment-btn" href="{{ route('login') }}">コメントを送信する</a>
            @endauth
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