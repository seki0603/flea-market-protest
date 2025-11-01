<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Flea Market</title>
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a href="{{ route('items.index') }}">
                <img class="header__logo" src="{{ asset('images/logo.svg') }}" alt="COACHTECH">
            </a>
        </div>
    </header>

    <main class="main">
        <div class="main__inner">
            @include('chat.components.sidebar', ['tradingOrders' => $tradingOrders])
            @livewire('chat-room', ['order' => $currentOrder, 'partner' => $partner])
        </div>
    </main>
    @livewireScripts
</body>

</html>