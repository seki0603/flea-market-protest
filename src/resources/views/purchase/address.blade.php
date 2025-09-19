@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="content">
    <h2 class="content__ttl">住所の変更</h2>
    <form class="form" action="{{ route('purchase.address.update', $product->id) }}" method="POST">
        @csrf
        <div class="form__section">
            <h3 class="form__section-ttl">郵便番号</h3>
            <input class="form__input" type="text" name="ship_postal_code" value="{{ old('ship_postal_code') }}">
            @error('ship_postal_code')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form__section">
            <h3 class="form__section-ttl">住所</h3>
            <input class="form__input" type="text" name="ship_address" value="{{ old('ship_address') }}">
            @error('ship_address')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form__section">
            <h3 class="form__section-ttl">建物名</h3>
            <input class="form__input" type="text" name="ship_building" value="{{ old('ship_building')}}">
        </div>
        <button class="form__btn">更新する</button>
    </form>
</div>
@endsection