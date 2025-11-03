<div class="content">
    {{-- 取引ヘッダー --}}
    <div class="chat-room__header">
        <div class="partner-user__wrapper">
            <img class="partner-user__image"
                src="{{ asset('storage/' . ($partner->profile->avatar_path ?? 'images/default-avatar.png')) }}"
                alt="取引相手プロフィール画像">
            <h2 class="partner-user__name">{{ $partner->name }}さんとの取引画面</h2>
        </div>
        @if ($order->buyer_id === auth()->id() && $order->status !== '取引完了')
        <button wire:click="$emit('openCompleteModal')" class="complete-button">取引を完了する</button>
        @endif
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

    <div class="chat-room">
        @foreach ($chatMessages as $chatMessage)
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
            <div class="send-image__wrapper">
                <img class="send-image" src="{{ asset('storage/' . $chatMessage->image_path) }}" alt="送信画像">
            </div>
            @endif
        </div>
        @else
        {{-- 自分のメッセージ --}}
        <form wire:submit.prevent="update({{ $chatMessage->id }})" class="message" novalidate>
            <div class="message__inner">
                <p class="message__name">{{ $chatMessage->sender->name }}</p>
                <img class="message__image"
                    src="{{ asset('storage/' . ($chatMessage->sender->profile->avatar_path ?? 'images/default-avatar.png')) }}"
                    alt="自分のプロフィール画像">
            </div>
            @error("updateMessage.{$chatMessage->id}")
            <p class="error">{{ $message }}</p>
            @enderror
            <textarea wire:model.defer="updateMessage.{{ $chatMessage->id }}" class="message__text"></textarea>
            <div class="button__wrapper">
                <button wire:click.prevent="update({{ $chatMessage->id }})" class="update-button" type="submit">
                    <span class="span">編集</span>
                </button>
                <button wire:click="delete({{ $chatMessage->id }})" class="delete-button" type="button">
                    <span class="span">削除</span>
                </button>
            </div>
            @if ($chatMessage->image_path)
            <div class="send-image__wrapper">
                <img class="send-image" src="{{ asset('storage/' . $chatMessage->image_path) }}" alt="送信画像">
            </div>
            @endif
        </form>
        @endif
        @endforeach
    </div>

    {{-- 送信フォーム --}}
    <form wire:submit.prevent="store" class="send-form" novalidate>
        @error('newMessage')
        <p class="error">{{ $message }}</p>
        @enderror
        @error('image')
        <p class="error">{{ $message }}</p>
        @enderror
        <div class="send-form__inner">
            <input wire:model.defer="newMessage" class="send-input" type="text" placeholder="取引メッセージを記入してください">
            <label class="file-input__label">画像を追加
                <input wire:model.defer="image" wire:key="file-input-{{ now() }}" class="file-input" type="file">
            </label>
            <button class="send-button" type="submit">
                <img class="send-button__image" src="{{ asset('images/inputbutton.jpg') }}" alt="送信ボタン">
            </button>
        </div>
    </form>
</div>