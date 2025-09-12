@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
{{-- タブ切り替え --}}
<div class="tab__wrapper">
    <div class="tab">
        <a href="{{ url('/?tab=recommend') }}"
            class="tab__link {{ request('tab') !== 'mylist' ? 'tab__link--active' : '' }}">
            おすすめ
        </a>
        <a href="{{ url('/?tab=mylist') }}"
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
            <img class="product__img" src="{{ asset('storage/'.$product->image_path) }}" alt="商品画像">
        </a>
            <p class="product__name">{{ $product->name }}</p>
            {{-- Sold判定 --}}
            @if($product->buyer_id || $product->sold_at)
            <span class="sold-label">Sold</span>
            @endif
    </div>
    @endforeach
</div>
@endsection