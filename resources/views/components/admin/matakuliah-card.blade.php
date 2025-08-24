    <div class="class-card mb-3" data-card-id="{{ $cardId }}">
        <div class="card-header d-flex justify-content-between align-items-start">
            <h5 class="card-title mb-0">{{ $title }}</h5>
            <button type="button" class="btn-close" onclick="removeCard('{{ $cardId }}', '{{ e(json_encode($data)) }}')" aria-label="Close">
                <img src="{{ asset('img/cross.png') }}" alt="Cross">
            </button>
        </div>
        <div class="card-body">
            <div class="card-requirements mb-3">
                <p class="mb-1"><strong>Ketentuan:</strong></p>
                <p class="requirement-item mb-1">{{ $requirements }}</p>  
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <p class="class-count mb-0"><strong>Jumlah Kelas Praktikum: {{ $classCount }}</strong></p>
                <div class="d-flex align-items-center action-group">
                    <button type="button" class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editClassDialog" data-card-id="{{ $cardId }}" data-card-data="{{ e(json_encode($data)) }}">
                        Edit
                    </button>
                    <div class="ms-2 d-inline-flex align-items-center gap-2">
                        @php $isActive = isset($data['status']) ? (bool)$data['status'] : false; @endphp
                        <span class="badge {{ $isActive ? 'bg-success' : 'bg-danger' }} status-badge">
                            {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <button type="button"
                                class="btn btn-sm btn-outline-secondary btn-toggle-status"
                                data-mk-id="{{ $cardId }}"
                                data-current-status="{{ $isActive ? 'aktif' : 'nonaktif' }}">
                            {{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .class-card {
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

    .card-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 18px;
        font-weight: 600;
        color: #333;
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
        padding: 0 20px 20px 20px;
    }

    .card-requirements p {
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        color: #333;
        margin: 0;
    }

    .requirement-item {
        margin-left: 8px;
    }

    .class-count {
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        color: #333;
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
    .action-group {
        gap: 8px;
        white-space: nowrap; /* prevent wrapping of edit + status */
    }
    .status-badge { font-size: 12px; }
    </style>
