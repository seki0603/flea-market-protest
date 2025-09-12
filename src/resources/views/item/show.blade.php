@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('title', '商品詳細')

@section('content')
<div class="item">
    {{-- 商品画像 --}}
    <div class="item__img-wrapper">
        <img class="item__img" src="{{ asset('storage/'.$product->image_path) }}" alt="商品画像">
        @if($product->buyer_id || $product->sold_at)
        <span class="sold-label">Sold</span>
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
                <form action="" method="POST">
                    @csrf
                    <button class="like-btn" type="submit">
                        <img class="like-btn__img" src="{{ asset('images/star.png') }}" alt="いいね">
                    </button>
                </form>
                @else
                <a class="like-btn" href="{{ route('login') }}">
                    <img class="like-btn__img" src="{{ asset('images/star.png') }}" alt="コメント">
                </a>
                @endauth
                <p class="like-count">{{ $product->likes->count() }}</p>
            </div>
            <div class="item__icons-inner">
                @auth
                <form action="" method="POST">
                    @csrf
                    <button class="comment-btn" type="submit">
                        <img class="comment-btn__img" src="{{ asset('images/speechbuble.png') }}" alt="いいね">
                    </button>
                </form>
                @else
                <a class="comment-btn" href="{{ route('login') }}">
                    <img class="comment-btn__img" src="{{ asset('images/speechbuble.png') }}" alt="コメント">
                </a>
                @endauth
                <p class="comment-count">{{ $product->likes->count() }}</p>
            </div>
        </div>

        {{-- 購入ボタン --}}
        <div class="item__buy">
            @auth
            <a class="item__buy-btn" href="">購入手続きへ</a>
            @else
            <a class="item__buy-btn" href="{{ route('login') }}">購入手続きへ</a>
            @endauth
        </div>

        {{-- 商品説明 --}}
        <div class="item__section">
            <h3 class="item__section-ttl">商品説明</h3>
            <p class="item__description">{{ $product->description }}</p>
        </div>

        {{-- 商品カテゴリ --}}
        <div class="item__section">
            <h3 class="item__section-ttl">商品の情報</h3>
            <ul class="item__info-list">
                @foreach($product->categories as $category)
                <li>{{ $category->name }}</li>
                @endforeach
                <li>状態：{{ $product->condition }}</li>
            </ul>
        </div>

        {{-- コメント欄 --}}
        <div class="item__section">
            <h3 class="item__section-ttl">コメント ({{ $product->comments->count() }})</h3>

            @foreach($product->comments as $comment)
            <div class="item__comment">
                <p class="item__comment-user">{{ $comment->user->name }}</p>
                <p class="item__comment-text">{{ $comment->content }}</p>
            </div>
            @endforeach

            @auth
            <form class="item__comment-form" action="" method="POST">
                @csrf
                <textarea class="item__comment-input" name="content" placeholder="商品へのコメント"></textarea>
                <button class="item__comment-btn" type="submit">コメントを送信する</button>
            </form>
            @else
            <a class="item__comment-btn" href="">ログインしてコメントする</a>
            @endauth
        </div>
    </div>
</div>
@endsection