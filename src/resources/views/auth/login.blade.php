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
      <h2 class="content__title">ログイン</h2>
      <form class="form" action="{{ route('login') }}" method="POST" >
        @csrf
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
            {{ $message }}
            @enderror
          </div>
        </div>
        <div class="form__button">
          <button class="form__button-submit">ログイン</button>
        </div>
      </form>
      <div class="content__link">
        <a class="content__link-text" href="{{ route('register') }}">会員登録はこちら</a>
      </div>
    </div>
  </main>
</body>

</html>