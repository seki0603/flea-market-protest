<div>
    @if ($isOpen)
    <div class="modal-overlay">
        <form wire:submit.prevent="store" class="modal" novalidate>
            <h2 class="modal__title">取引が完了しました。</h2>
            <div class="rating">
                <p class="rating__text">今回の取引相手はどうでしたか？</p>
                @error('score')
                    <p class="error">{{ $message }}</p>
                @enderror
                <div class="rating__inner">
                    @for ($index = 5; $index >= 1; $index--)
                    <input wire:model="score" class="rating-input" type="radio" id="star{{ $index }}" name="score" value="{{ $index }}" />
                    <label class="rating-label" for="star{{ $index }}">★</label>
                    @endfor
                </div>
            </div>
            <div class="modal-button__wrapper">
                <button class="modal-button" type="submit">送信する</button>
            </div>
        </form>
    </div>
    @endif
</div>