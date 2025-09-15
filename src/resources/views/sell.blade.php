@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="form__wrapper">
    <h2 class="form__ttl">商品の出品</h2>
    <form class="form" action="">
        {{-- 画像アップロード --}}
        <p class="form__image-ttl">商品画像</p>
        <div class="form__image">
            <button class="form__image-btn">画像を選択する</button>
        </div>

        <h3 class="form__section-ttl">商品の詳細</h3>

        {{-- カテゴリー --}}
        <p class="form__category-ttl">カテゴリー</p>
        <div class="form__category-list">
            @foreach($categories as $category)
            <label class="form__category-label">
                <input class="form__category" type="checkbox" name="categories[]" value="{{ $category->id }}">
                <span class="form__category-name">{{ $category->name }}</span>
            </label>
            @endforeach
        </div>

        {{-- 状態 --}}
        <p class="form__condition-ttl">商品の状態</p>
        <div class="form__condition">
            <input type="hidden" name="condition" id="conditionValue">
            <div class="form__condition-selectbox" id="conditionSelectbox">
                <div class="form__condition-selected">選択してください</div>
                <ul class="form__condition-options">
                    <li data-value="1">良好</li>
                    <li data-value="2">目立った傷や汚れなし</li>
                    <li data-value="3">やや傷や汚れあり</li>
                    <li data-value="4">状態が悪い</li>
                </ul>
            </div>
        </div>


        <h3 class="form__section-ttl">商品名と説明</h3>

        {{-- その他のカラム --}}
        <p class="form__name-ttl">商品名</p>
        <input class="form__name" type="text" name="name">

        <p class="form__brand-ttl">ブランド名</p>
        <input class="form__brand" type="text" name="brand_name">

        <p class="form__description-ttl">商品の説明</p>
        <textarea class="form__description" name="description"></textarea>

        <p class="form__price-ttl">販売価格</p>
        <input class="form__price" type="text" name="price">
        <button class="form__button" type="submit">出品する</button>
    </form>
</div>
@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", () => {
    const selectbox = document.getElementById('conditionSelectbox');
    const selected = selectbox.querySelector('.form__condition-selected');
    const options = selectbox.querySelectorAll('.form__condition-options li');
    const hiddenInput = document.getElementById('conditionValue');

    selected.addEventListener('click', () => {
        selectbox.classList.toggle('open');
    });

    options.forEach(opt => {
        opt.addEventListener('click', () => {
            options.forEach(o => o.classList.remove('selected'));
            opt.classList.add('selected');
            selected.textContent = opt.textContent;
            hiddenInput.value = opt.dataset.value;
            selectbox.classList.remove('open');
        });
    });
});
</script>
@endsection