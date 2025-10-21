@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="content">
    <h1 class="content__title">住所の変更</h1>
    <form class="form" action="{{ route('purchase.address.update', $product->id) }}" method="POST" novalidate>
        @csrf
        <div class="form__section">
            <p class="form__section-title">郵便番号</p>
            <input class="form__input" type="text" name="ship_postal_code" value="{{ old('ship_postal_code') }}">
            @error('ship_postal_code')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form__section">
            <p class="form__section-title">住所</p>
            <input class="form__input" type="text" name="ship_address" value="{{ old('ship_address') }}">
            @error('ship_address')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <div class="form__section">
            <p class="form__section-title">建物名</p>
            <input class="form__input" type="text" name="ship_building" value="{{ old('ship_building')}}">
        </div>
        <button class="form__button" type="submit">更新する</button>
    </form>
</div>
@endsection