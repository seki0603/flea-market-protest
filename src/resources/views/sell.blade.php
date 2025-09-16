@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="form__wrapper">
    <h2 class="form__ttl">商品の出品</h2>
    <form class="form" action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- 画像アップロード --}}
        <p class="form__image-ttl">商品画像</p>
        <div class="form__image-wrapper">
            <div class="form__image" id="image-container">
                <input id="image" type="file" name="image" hidden>
                <label class="form__image-btn" for="image" id="image-label">画像を選択する</label>
                <div class="form__image-preview" id="image-preview"></div>
            </div>
            <button class="form__reset-btn" type="button" id="reset-btn" style="display: none">画像を選び直す</button>
            @error('image')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <h3 class="form__section-ttl">商品の詳細</h3>

        {{-- カテゴリー --}}
        <p class="form__category-ttl">カテゴリー</p>
        <div class="form__category-list">
            @foreach($categories as $category)
            <label class="form__category-label">
                <input class="form__category" type="checkbox" name="categories[]" value="{{ $category->id }}" {{
                    in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                <span class="form__category-name">{{ $category->name }}</span>
            </label>
            @endforeach
        </div>
        @error('categories')
        <p class="error">{{ $message }}</p>
        @enderror

        {{-- 状態 --}}
        <p class="form__condition-ttl">商品の状態</p>
        <div class="form__condition">
            <input type="hidden" name="condition" id="conditionValue" value="{{ old('condition') }}">
            <div class="form__condition-selectbox" id="conditionSelectbox">
                <div class="form__condition-selected">
                    {{ old('condition') !== null ? ['良好','目立った傷や汚れなし','やや傷や汚れあり','状態が悪い'][old('condition')] : '選択してください' }}
                </div>
                <ul class="form__condition-options">
                    <li data-value="0">良好</li>
                    <li data-value="1">目立った傷や汚れなし</li>
                    <li data-value="2">やや傷や汚れあり</li>
                    <li data-value="3">状態が悪い</li>
                </ul>
            </div>
            @error('condition')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <h3 class="form__section-ttl">商品名と説明</h3>

        {{-- その他のカラム --}}
        <p class="form__name-ttl">商品名</p>
        <input class="form__name" type="text" name="name" value="{{ old('name') }}">
        @error('name')
        <p class="error">{{ $message }}</p>
        @enderror

        <p class="form__brand-ttl">ブランド名</p>
        <input class="form__brand" type="text" name="brand_name" value="{{ old('brand_name') }}">
        @error('brand_name')
        <p class="error">{{ $message }}</p>
        @enderror

        <p class="form__description-ttl">商品の説明</p>
        <textarea class="form__description" name="description">{{ old('description') }}</textarea>
        @error('description')
        <p class="error">{{ $message }}</p>
        @enderror

        <p class="form__price-ttl">販売価格</p>
        <div class="form__price-wrapper">
            <div class="form__price-content">
                <span class="form__price-symbol">¥</span>
                <input class="form__price" type="text" name="price" id="priceInput" value="{{ old('price') }}"
                    inputmode="numeric" pattern="\d*" autocomplete="off">
            </div>
            @error('price')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>
        <button class="form__button" type="submit">出品する</button>
    </form>
</div>
@endsection

@section('script')
{{-- カスタムセレクト --}}
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

{{-- 金額入力 --}}
<script>
(function () {
    const el = document.getElementById('priceInput');
    if (!el) return;
    el.addEventListener('input', () => {
        let v = el.value || '';
        v = v.replace(/[０-９]/g, (s) =>
            String.fromCharCode(s.charCodeAt(0) - 0xFEE0)
        );
        v = v.replace(/[^0-9]/g, '');
        el.value = v;
    });
})();
</script>

{{-- 画像プレビュー --}}
<script>
const input = document.getElementById('image');
const preview = document.getElementById('image-preview');
const label = document.getElementById('image-label');
const resetBtn = document.getElementById('reset-btn');

input.addEventListener('change', function() {
    preview.innerHTML = '';
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            preview.appendChild(img);

            label.style.display = 'none';
            resetBtn.style.display = 'inline-block';
        }
        reader.readAsDataURL(this.files[0]);
    }
});

resetBtn.addEventListener('click', function(e) {
    e.preventDefault();
    input.value = '';
    preview.innerHTML = '';
    label.style.display = 'inline-block';
    resetBtn.style.display = 'none';
});
</script>
@endsection