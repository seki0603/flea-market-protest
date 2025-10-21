@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="tab__wrapper">
    <div class="tab">
        <h1 class="visually-hidden">商品一覧画面</h1>
        {{-- タブ切り替え --}}
        @if (session('message'))
        <p class="success">{{ session('message') }}</p>
        @endif
        <a href="{{ request('keyword') ? url('/?keyword=' . request('keyword')) : url('/') }}"
            class="tab__link {{ request('tab') !== 'mylist' ? 'tab__link--active' : '' }}">
            おすすめ
        </a>
        <a href="{{ request('keyword') ? url('/?tab=mylist&keyword=' . request('keyword')) : url('/?tab=mylist') }}"
            class="tab__link {{ request('tab') === 'mylist' ? 'tab__link--active' : '' }}">
            マイリスト
        </a>
    </div>
</div>

{{-- 商品一覧 --}}
<div class="products">
    @foreach($products as $product)
    <div class="product">
        <a href="{{ route('item.show', $product->id) }}">
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
        </a>
        <p class="product__name">{{ $product->name }}</p>
    </div>
    @endforeach
</div>
@endsection