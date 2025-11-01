<div class="content">
    {{-- 取引ヘッダー --}}
    <div class="chat-room__header">
        <div class="partner-user__wrapper">
            <img class="partner-user__image"
                src="{{ asset('storage/' . ($partner->profile->avatar_path ?? 'images/default-avatar.png')) }}"
                alt="取引相手プロフィール画像">
            <h2 class="partner-user__name">{{ $partner->name }}さんとの取引画面</h2>
        </div>
        <button class="complete-button">取引を完了する</button>
    </div>

    {{-- 商品情報 --}}
    <div class="product__wrapper">
        <img class="product__image" src="{{ \Illuminate\Support\Str::startsWith($order->product->image_path, 'http')
    ? $order->product->image_path : asset('storage/'.$order->product->image_path) }}" alt="取引商品画像">
        <div class="product__inner">
            <h1 class="product__name">{{ $order->product->name }}</h1>
            <h3 class="product__price">{{ number_format($order->product->price) }}円</h3>
        </div>
    </div>

    {{-- チャット一覧 --}}
    <div class="chat-room">
        @foreach ($order->chatRoom->chatMessages as $chatMessage)
        @php
        $isMine = $chatMessage->sender_id === auth()->id();
        @endphp

        {{-- 相手からのメッセージ --}}
        @if (! $isMine)
        <div class="partner-message">
            <div class="partner-message__inner">
                <img class="partner-message__image"
                    src="{{ asset('storage/' . ($chatMessage->sender->profile->avatar_path ?? 'images/default-avatar.png')) }}"
                    alt="取引相手プロフィール画像">
                <p class="partner-message__name">{{ $chatMessage->sender->name }}</p>
            </div>
            <p class="partner-message__text">{{ $chatMessage->message }}</p>

            @if ($chatMessage->image_path)
            <img class="partner-message__image-content" src="{{ asset('storage/' . $chatMessage->image_path) }}" alt="送信画像">
            @endif
        </div>
        @else
        {{-- 自分のメッセージ --}}
        <div class="message">
            <div class="message__inner">
                <p class="message__name">{{ $chatMessage->sender->name }}</p>
                <img class="message__image"
                    src="{{ asset('storage/' . ($chatMessage->sender->profile->avatar_path ?? 'images/default-avatar.png')) }}"
                    alt="自分のプロフィール画像">
            </div>
            <p class="message__text">{{ $chatMessage->message }}</p>

            @if ($chatMessage->image_path)
            <img class="message__image-content" src="{{ asset('storage/' . $chatMessage->image_path) }}" alt="送信画像">
            @endif

            <div class="button__wrapper">
                <button class="update-button" wire:click.prevent="edit({{ $chatMessage->id }})">
                    <span class="span">編集</span>
                </button>
                <button class="delete-button" wire:click.prevent="delete({{ $chatMessage->id }})">
                    <span class="span">削除</span>
                </button>
            </div>
        </div>
        @endif
        @endforeach
    </div>

    {{-- 送信フォーム --}}
    <form wire:submit.prevent="sendMessage" class="send-form" novalidate>
        <input wire:model.defer="newMessage" class="send-input" type="text" placeholder="取引メッセージを記入してください">
        <button type="button" class="upload-button" wire:click="uploadImage">画像を追加</button>
        <button type="submit" class="send-button">
            <img class="send-button__image" src="{{ asset('images/inputbutton.jpg') }}" alt="送信ボタン">
        </button>
    </form>
</div>