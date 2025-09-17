@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="form__wrapper">
    <h2 class="form__ttl">プロフィール設定</h2>
    <form class="form" action="" method="" enctype="multipart/form-data">

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

        <p class="form__name-ttl">ユーザー名</p>
        <input class="form__name" type="text" name="name" value="{{ old('name', $user->name) }}">
        <p class="form__post-ttl">郵便番号</p>
        <input class="form__post" type="text" name="postal_code"
            value="{{ old('postal_code', $user->profile->postal_code ?? '') }}">
        <p class="form__address-ttl">住所</p>
        <input class="form__address" type="text" name="address"
            value="{{ old('address', $user->profile->address ?? '') }}">
        <p class="form__building-ttl">建物名</p>
        <input class="form__building" type="text" name="building"
            value="{{ old('building', $user->profile->building ?? '') }}">
        <button class="form__btn" type="submit">更新する</button>
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