    <div class="class-card mb-3" data-card-id="{{ $cardId }}" data-mk-id="{{ $mkId }}" >
        @php
            // Support both object and array shapes for $data
            $kodeKelas = isset($data->kode_kelas) ? $data->kode_kelas : (isset($data['kode_kelas']) ? $data['kode_kelas'] : '');
            $kodeEnroll = isset($data->kode_enroll) ? $data->kode_enroll : (isset($data['kode_enroll']) ? $data['kode_enroll'] : '');
        @endphp
        <div class="card-header d-flex justify-content-between align-items-start">
            <div class="title-and-badges">
                <h5 class="card-title mb-1">{{ $title }}</h5>
                @if($kodeKelas || $kodeEnroll)
                <div class="code-badges mt-1">
                    @if($kodeKelas)
                        <span class="badge-code">Kelas: {{ $kodeKelas }}</span>
                    @endif
                    @if($kodeEnroll)
                        <span class="badge-code">Enroll: {{ $kodeEnroll }}</span>
                    @endif
                </div>
                @endif
            </div>
            <button type="button" class="btn-close" onclick="removeCard('{{ $cardId }}', '{{ e(json_encode($data)) }}')" aria-label="Close">
                <img src="{{ asset('img/cross.png') }}" alt="Cross">
            </button>
        </div>
        <div class="card-body">
            @if((isset($requirements) && trim((string)$requirements) !== '') || $kodeKelas || $kodeEnroll)
            <div class="card-requirements mb-3">
                @if(isset($requirements) && trim((string)$requirements) !== '')
                    <p class="mb-1"><strong>Ketentuan:</strong></p>
                    <p class="requirement-item mb-1">{{ $requirements }}</p>
                @endif

                {{-- codes are shown as badges in the header --}}
            </div>
            @endif
            <div class="d-flex justify-content-between align-items-center">
                <p class="class-count mb-0"><strong>Jumlah Kelas Praktikum: {{ $classCount }}</strong></p>
                <button type="button" class="btn btn-edit" data-card-id="{{ $cardId }}" data-card-data="{{ e(json_encode($data)) }}">
                    Edit
                </button>
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

    /* Requirements and code info should share identical font styling */
    .card-requirements,
    .card-requirements p,
    .card-requirements .requirement-item {
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        color: #333;
        font-weight: 400;
        margin: 0;
        line-height: 1.4;
    }

    .card-requirements .requirement-item {
        margin-left: 8px;
    }

    .card-requirements p strong {
        font-weight: 700; /* make labels like "Ketentuan:" and "Informasi Kode:" bolder */
    }

    .code-badges {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .badge-code {
        display: inline-block;
        background: #f1f3f5;
        color: #333;
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 500;
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
    </style>
