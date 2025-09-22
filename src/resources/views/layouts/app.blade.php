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
                <a href="{{ route('items.index') }}">
                    <img class="header__logo-img" src="{{ asset('images/logo.svg') }}" alt="COACHTECH">
                </a>
            </div>
            <form class="header__form" action="{{ route('items.index') }}" method="GET">
                <input class="header__form-input" type="text" name="keyword" value="{{ request('keyword') }}"
                    placeholder="なにをお探しですか？">
                <button class="header__form-btn" type="submit">検索</button>
            </form>
            <div class="header__link">

                @guest
                <a class="header__link-login" href="{{ route('login') }}">ログイン</a>
                <a class="header__link-mypage" href="{{ route('login') }}">マイページ</a>
                <a class="header__link-purchase" href="{{ route('login') }}">出品</a>
                @endguest

                @auth
                <form class="header__btn" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="header__btn-logout" type="submit">ログアウト</button>
                </form>
                <a class="header__link-mypage" href="{{ route('profile.index') }}">マイページ</a>
                <a class="header__link-purchase" href="{{ route('sell.index') }}">出品</a>
                @endauth

            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
    @yield('script')
</body>

</html>