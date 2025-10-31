<aside class="sidebar">
    <h2 class="sidebar__title">その他の取引</h2>
    @foreach ($tradingProducts as $product)
    <a class="sidebar__link" href="{{ route('chat.index', $product->order->chatRoom->id ?? '#') }}">
        {{ $product->name }}
    </a>
    @endforeach
</aside>