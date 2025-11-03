@component('mail::message')
# 取引が完了しました

{{ $order->buyer->name }}さんとの取引が完了しました。
チャットルームからユーザーを評価してください。

@component('mail::button', ['url' => route('chat.index', $order->chatRoom->id)])
チャットルームを開く
@endcomponent

@endcomponent