@component('mail::message')
# 取引が完了しました

{{ $order->buyer->name }}さんとの取引が完了しました。
チャットルームからユーザーの評価にご協力ください。

@component('mail::button', ['url' => route('chat.index', $order->chatRoom->id)])
チャットルームを開く
@endcomponent

@endcomponent