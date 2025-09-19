@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<form class="content">
    <div class="select-content">

        <div class="product">
            <img class="product__img" src="{{ asset('storage/'.$product->image_path) }}" alt="商品画像">
            <div class="product__inner">
                <h3 class="product__name">{{ $product->name }}</h3>
                <p class="product__price">
                    <span class="product__price--span">¥</span>
                    {{ number_format($product->price) }}
                </p>
            </div>
        </div>

        <div class="payment">
            <h3 class="payment__ttl">支払い方法</h3>
            <div class="payment__inner">
                <input type="hidden" name="payment_method" id="paymentMethodValue" value="{{ old('payment_method') }}">
                <div class="payment-selectbox" id="paymentSelectbox">
                    <div class="payment-selected">
                        {{ old('payment_method') ?? '選択してください' }}
                    </div>
                    <ul class="payment-options">
                        <li data-value="コンビニ支払い">コンビニ払い</li>
                        <li data-value="カード支払い">カード支払い</li>
                    </ul>
                </div>
                @error('payment_method')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="ship-address">
            <div class="ship-address__header">
                <h3 class="ship-address__ttl">配送先</h3>
                <a class="ship-address__change" href="{{ route('purchase.address', $product->id) }}">変更する</a>
            </div>
            <div class="ship-address__inner">
                <p class="postal-code">〒{{ session('ship_postal_code', $user->profile->postal_code) }}</p>
                <p class="address">{{ session('ship_address', $user->profile->address) }}</p>
                <p class="building">{{ session('ship_building', $user->profile->building) }}</p>
                @if (session('message'))
                <p class="success">{{ session('message') }}</p>
                @endif
                @error('')
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
<script>
    document.addEventListener("DOMContentLoaded", () => {
    const selectbox = document.getElementById('paymentSelectbox');
    const selected = selectbox.querySelector('.payment-selected');
    const options = selectbox.querySelectorAll('.payment-options li');
    const hiddenInput = document.getElementById('paymentMethodValue');
    const paymentText = document.getElementById('paymentMethodText');

    selected.addEventListener('click', () => {
        selectbox.classList.toggle('open');
    });

    options.forEach(opt => {
        opt.addEventListener('click', () => {
            options.forEach(o => o.classList.remove('selected'));
            opt.classList.add('selected');
            selected.textContent = opt.textContent;
            hiddenInput.value = opt.dataset.value;

            if (paymentText) {
                paymentText.textContent = opt.textContent;
            }

            selectbox.classList.remove('open');
        });
    });
});
</script>
@endsection