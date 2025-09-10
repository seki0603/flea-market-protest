<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Flea Market</title>
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <img src="" alt="COACHTECH">
    </div>
  </header>

  <main>
    <div class="content">
      <h2 class="content__title">会員登録</h2>
      <form class="form" action="{{ route('register.store') }}" method="POST">
        @csrf
        <div class="form__item">
          <label class="form__item-label">ユーザー名</label>
          <input class="form__item-input" type="text" name="name">
          <div class="error">
            @error('name')
            {{ $message }}
            @enderror
          </div>
        </div>
        <div class="form__item">
          <label class="form__item-label">メールアドレス</label>
          <input class="form__item-input" type="email" name="email">
          <div class="error">
            @error('email')
            {{ $message }}
            @enderror
          </div>
        </div>
        <div class="form__item">
          <label class="form__item-label">パスワード</label>
          <input class="form__item-input" type="password" name="password">
          <div class="error">
            @error('password')
            @if ($message !== 'パスワードと一致しません')
            {{ $message }}
            @endif
            @enderror
          </div>
        </div>
        <div class="form__item">
          <label class="form__item-label">確認用パスワード</label>
          <input class="form__item-input" type="password" name="password_confirmation">
          <div class="error">
            @error('password')
            @if ($message === 'パスワードと一致しません')
            {{ $message }}
            @endif
            @enderror
          </div>
          <div class="form__button">
            <button class="form__button-submit" type="submit">登録する</button>
          </div>
      </form>
      <div class="content__link">
        <a class="content__link-text" href="{{ route('login') }}">ログインはこちら</a>
      </div>
    </div>
  </main>
</body>

</html>