@php
    // Normalize status whether $data is a model or array
    $status = 0;
    if (is_array($data)) {
        $status = isset($data['status']) ? (int) $data['status'] : 0;
    } else {
        $status = isset($data->status) ? (int) $data->status : 0;
    }
@endphp

<div class="announcement-card mb-3" data-card-id="{{ $cardId }}">
    <div class="card-header d-flex justify-content-between align-items-start">
        <div class="title-section">
            <h5 class="card-title mb-0">{{ $title }}</h5>
            <div class="dotted-separator"></div>
        </div>

        <button type="button" class="btn-close" onclick="removeCard('{{ $cardId }}', '{{ e(json_encode($data)) }}')" aria-label="Close">
            <img src="{{ asset('img/cross.png') }}" alt="Cross">
        </button>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-end align-items-center">
            @php
                $label = $status ? 'Aktif' : 'Nonaktif';
                $badgeClass = $status ? 'status-active' : 'status-inactive';
            @endphp
            <span class="status-badge {{ $badgeClass }} me-2">{{ $label }}</span>
            <button type="button" class="btn btn-sm btn-outline-secondary btn-toggle-status me-2" data-pengumuman-id="{{ $cardId }}" data-current-status="{{ $status ? '1' : '0' }}">
                {{ $status ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
            <button type="button" class="btn btn-edit" data-card-id="{{ $cardId }}" data-card-data='@json($data)'>
                Edit
            </button>
        </div>
    </div>
</div>

<style>
.announcement-card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0px 4px 4px rgba(117, 117, 117, 0.3);
    border: 1px solid;
    margin-bottom: 16px;
    overflow: hidden;
}

.card-header {
    background: transparent;
    border: none;
    padding: 20px 20px 0 20px;
}

.title-section {
    flex: 1;
}

.card-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 18px;
    font-weight: 600;
    color: #333;
    line-height: 1.3;
    margin-bottom: 8px;
}

.dotted-separator {
    border-bottom: 2px dotted #ccc;
    width: 100%;
    margin-top: 8px;
}

.btn-close {
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

.btn-close:hover {
    color: #000;
}

.card-body {
    padding: 20px 20px 20px 20px;
}

.btn-edit {
    background-color: var(--secondary);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 500;
    min-width: 60px;
}

.btn-edit:hover {
    background-color: var(--secondary-orange800);
    color: white;
}
.status-badge {
    display: inline-block;
    border-radius: 8px;
    padding: 4px 8px;
    font-weight: 600;
    font-size: 12px;
    margin-top: 6px;
}
.status-active {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #bbf7d0;
}
.status-inactive {
    background: #fff7ed;
    color: #92400e;
    border: 1px solid #fed7aa;
}
</style>