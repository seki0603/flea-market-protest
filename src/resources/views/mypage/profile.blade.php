@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="form__wrapper">
    @if (session('message'))
    <p class="success">{{ session('message') }}</p>
    @endif
    <h2 class="form__title">プロフィール設定</h2>
    <form class="form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="form__avatar-wrapper">
            <div class="form__avatar">
                <div class="form__avatar-preview">
                    @if($user->profile?->avatar_path)
                    <img class="form__avatar-img" id="avatarPreview"
                        src="{{ asset('storage/' . $user->profile->avatar_path) }}" alt="プロフィール画像">
                    @else
                    <img class="form__avatar-img" id="avatarPreview" style="display:none;">
                    @endif
                </div>
                <label class="form__avatar-label">
                    画像を選択する
                    <input class="form__avatar-input" id="avatarInput" type="file" name="avatar" accept="image/*">
                </label>
            </div>
            <div class="error">
                @error('avatar')
                {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form__name-wrapper">
            <p class="form__name-title">ユーザー名</p>
            <input class="form__name" type="text" name="name" value="{{ old('name', $user->name) }}">
            <div class="error">
                @error('name')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__post-wrapper">
            <p class="form__post-title">郵便番号</p>
            <input class="form__post" type="text" name="postal_code"
                value="{{ old('postal_code', $user->profile->postal_code ?? '') }}">
            <div class="error">
                @error('postal_code')
                {{ $message }}
                @enderror
            </div>
        </div>
        <div class="form__address-wrapper">
            <p class="form__address-title">住所</p>
            <input class="form__address" type="text" name="address"
                value="{{ old('address', $user->profile->address ?? '') }}">
            <div class="error">
                @error('address')
                {{ $message }}
                @enderror
            </div>
        </div>
        <p class="form__building-title">建物名</p>
        <input class="form__building" type="text" name="building"
            value="{{ old('building', $user->profile->building ?? '') }}">
        <button class="form__button" type="submit">更新する</button>
    </form>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('avatarInput');
    const preview = document.getElementById('avatarPreview');

    input.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    });
});
</script>
@endsection