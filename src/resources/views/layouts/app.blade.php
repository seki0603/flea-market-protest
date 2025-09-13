<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Flea Market</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__logo">
                <img class="header__logo-img" src="{{ asset('images/logo.svg') }}" alt="COACHTECH">
            </div>
            <form class="header__form" action="">
                <input class="header__form-input" type="text" placeholder="なにをお探しですか？">
            </form>
            <div class="header__link">

                @guest
                <a class="header__link-login" href="{{ route('login') }}">ログイン</a>
                <a class="header__link-mypage" href="{{ route('login') }}">マイページ</a>
                <a class="header__link-seller" href="{{ route('login') }}">出品</a>
                @endguest

                @auth
                <form class="header__button" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="header__button-logout" type="submit">ログアウト</button>
                </form>
                <a class="header__link-mypage" href="">マイページ</a>
                <a class="header__link-seller" href="">出品</a>
                @endauth

            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>