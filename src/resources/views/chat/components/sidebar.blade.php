<aside class="sidebar">
    <h2 class="sidebar__title">その他の取引</h2>
    @foreach ($tradingOrders as $order)
    <a class="sidebar__link" href="{{ route('chat.index', $order->chatRoom->id ?? '#') }}">
        {{ $order->product->name }}
    </a>
    @endforeach
</aside>