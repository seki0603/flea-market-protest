@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form class="content" action="{{ route('purchase.store', $product->id) }}" method="POST" novalidate>
    @csrf
    <div class="select-content">
        <h1 class="visually-hidden">商品購入画面</h1>
        <div class="product">
            <img class="product__img" src="{{ \Illuminate\Support\Str::startsWith($product->image_path, 'http')
    ? $product->image_path : asset('storage/'.$product->image_path) }}" alt="商品画像">
            <div class="product__inner">
                <h2 class="product__name">{{ $product->name }}</h2>
                <p class="product__price">
                    <span class="product__price--span">¥</span>
                    {{ number_format($product->price) }}
                </p>
            </div>
        </div>

        <div class="payment">
            <h2 class="payment__title">支払い方法</h2>
            <div class="payment__inner">
                <input type="hidden" name="payment_method" id="paymentMethodValue" value="{{ old('payment_method') }}">
                <div class="payment__selectbox" id="paymentSelectbox">
                    <div class="payment__selected">
                        {{ old('payment_method') ?? '選択してください' }}
                    </div>
                    <ul class="payment__options">
                        <li class="payment__option" data-value="コンビニ支払い">コンビニ支払い</li>
                        <li class="payment__option" data-value="カード支払い">カード支払い</li>
                    </ul>
                </div>
                @error('payment_method')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="ship-address">
            <div class="ship-address__header">
                <h2 class="ship-address__title">配送先</h2>
                <a class="ship-address__change" href="{{ route('purchase.address', $product->id) }}">変更する</a>
            </div>
            <div class="ship-address__inner">
                <p class="postal-code">〒{{ session('ship_postal_code', $user->profile->postal_code) }}</p>
                <p class="address">{{ session('ship_address', $user->profile->address) }}</p>
                <p class="building">{{ session('ship_building', $user->profile->building) }}</p>
                @if (session('message'))
                <p class="success">{{ session('message') }}</p>
                @endif
                @error('ship_address')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <input type="hidden" name="ship_postal_code" value="{{ session('ship_postal_code', $user->profile->postal_code) }}">
            <input type="hidden" name="ship_address" value="{{ session('ship_address', $user->profile->address) }}">
            <input type="hidden" name="ship_building" value="{{ session('ship_building', $user->profile->building) }}">
        </div>
    </div>

    <div class="result-content">
        <table class="table">
            <tr class="table__row">
                <th class="table__header">商品代金</th>
                <td class="table__item">
                    <span class="table__item--span">¥</span>
                    {{ number_format($product->price) }}
                </td>
            </tr>
            <tr class="table__row">
                <th class="table__header">支払方法</th>
                <td class="table__item" id="paymentMethodText">未選択</td>
            </tr>
        </table>
        <button class="purchase-btn" type="submit">購入する</button>
    </div>
</form>
@endsection

@section('script')
{{-- カスタムセレクト --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const selectbox = document.getElementById('paymentSelectbox');
    const selected = selectbox.querySelector('.payment__selected');
    const options = selectbox.querySelectorAll('.payment__option');
    const hiddenInput = document.getElementById('paymentMethodValue');
    const paymentText = document.getElementById('paymentMethodText');

    selected.addEventListener('click', () => {
        selectbox.classList.toggle('payment__selectbox--open');
    });

    options.forEach(opt => {
        opt.addEventListener('click', () => {
            options.forEach(o => o.classList.remove('payment__option--selected'));
            opt.classList.add('payment__option--selected');
            selected.textContent = opt.textContent;
            hiddenInput.value = opt.dataset.value;

            if (paymentText) {
                paymentText.textContent = opt.textContent;
            }

            selectbox.classList.remove('payment__selectbox--open');
        });
    });
});
</script>
@endsection