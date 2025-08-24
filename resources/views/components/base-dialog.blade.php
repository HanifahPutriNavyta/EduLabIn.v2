<div class="modal fade" id="{{ $dialogId }}" tabindex="-1" aria-labelledby="{{ $dialogId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ $size ?? '' }}">
        <div class="modal-content dialog-content">
            <div class="modal-header dialog-header">
                <h5 class="modal-title dialog-title" id="{{ $dialogId }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close dialog-close" data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('img/cross.png') }}" alt="Cross">
                </button>
            </div>
            <div class="modal-body dialog-body">
                {{ $slot }}
            </div>
            @if(isset($footer))
                <div class="modal-footer dialog-footer">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>

.dialog-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
}

.dialog-header {
    background: transparent;
    border: none;
    padding: 24px 24px 0 24px;
}

.dialog-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.dialog-close {
    background: none;
    border: none;
    font-size: 20px;
    color: #666;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dialog-close:hover {
    color: #000;
}

.dialog-body {
    padding: 20px 24px;
}

.dialog-footer {
    background: transparent;
    border: none;
    padding: 0 24px 24px 24px;
}

.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.6);
}
</style>
