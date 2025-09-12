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
            <div class="header__logo">
                <img src="" alt="COACHTECH">
            </div>
        </div>
        <form class="header__button" action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="header__button-link" type="submit">logout</button>
        </form>
    </header>

    <main>
        <div>プロフィール編集画面</div>
    </main>
</body>

</html>