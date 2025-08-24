@extends('layouts.app')

@section('title', 'Kelas Praktikum')
@push('styles')
<link href="{{ asset('css/asprak.css') }}" rel="stylesheet">
<style>
    /* Modal overlay */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.7);
        z-index: 1050;
        display: none;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.active {
        display: flex;
    }
    /* Modal box */
    .custom-modal {
        background: #fff;
        border-radius: 10px;
        padding: 24px 24px 18px 24px;
        min-width: 340px;
        max-width: 90vw;
        box-shadow: 0 4px 24px rgba(0,0,0,0.18);
        position: relative;
        animation: popIn 0.2s;
    }
    @keyframes popIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .custom-modal .close-btn {
        position: absolute;
        top: 12px;
        right: 14px;
        background: none;
        border: none;
        font-size: 20px;
        color: #222;
        cursor: pointer;
    }
    .custom-modal label {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
        display: block;
    }
    .custom-modal input[type="text"] {
        width: 100%;
        border: 1.5px solid #ccc;
        border-radius: 6px;
        padding: 8px 10px;
        font-size: 14px;
        margin-bottom: 18px;
        outline: none;
        transition: border 0.2s;
    }
    .custom-modal input[type="text"]:focus {
        border: 1.5px solid #ff9900;
    }
    .custom-modal .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .custom-modal .modal-btn {
        min-width: 60px;
        padding: 5px 0;
        border-radius: 5px;
        border: none;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        background: #ff9900;
        color: #fff;
        transition: background 0.2s;
    }
    .custom-modal .modal-btn.cancel {
        background: #fff;
        color: #ff9900;
        border: 1.5px solid #ff9900;
    }
    .custom-modal .modal-btn.cancel:hover {
        background: #ffe5b3;
    }
    .custom-modal .modal-btn:not(.cancel):hover {
        background: #e68a00;
    }
    /* Success modal */
    .custom-modal.success-modal {
        min-width: 260px;
        text-align: center;
        padding: 28px 18px 18px 18px;
    }
    .custom-modal.success-modal .modal-btn {
        margin-top: 10px;
        width: 60px;
        background: #ff9900;
        color: #fff;
    }
    /* Error modal styling (you might already have this, just ensuring it's here) */
    .custom-modal.error-modal {
        min-width: 260px;
        text-align: center;
        padding: 28px 18px 18px 18px;
    }
    .custom-modal.error-modal .modal-btn {
        margin-top: 10px;
        width: 60px;
        background: #ff9900; /* Or a red color for error buttons */
        color: #fff;
    }
    .fab-button {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background-color: var(--secondary);
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        z-index: 99999 !important;
        pointer-events: auto !important;
    }
    .fab-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        background-color: var(--secondary-orange800);
    }
    .fab-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
</style>
@endpush

@section('content')

<h1 class="judul-halaman">Kelas Praktikum</h1>

<div class="search-container mb-3 position-relative">
    <input type="text" class="form-control search-input" placeholder="Cari Mata Kuliah" aria-label="Search">
    <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
</div>

<div class="line-div"></div>

<div class="matkul-list">
    @foreach($kelas as $k)
    <a href="{{ route('dashboard.indexAsprakKelas', ['kode' => $k->kelas_id]) }}" class="matkul-item-link">
        <div class="matkul-item">
            {{$k->mataKuliah->nama_mk}}- {{$k->kode_kelas}}
        </div>
    </a>
    @endforeach
    @if($kelas->isEmpty())
    <div class="matkul-item">
        <p>Anda belum mengikuti kelas apapun, silahkan enroll</p>
    </div>
    @endif
</div>

<button class="fab-button" onclick="openJoinKelasModal()">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
</button>

<div class="modal-overlay" id="joinKelasModal">
    <div class="custom-modal">
        <button class="close-btn" onclick="closeJoinKelasModal()">&times;</button>
        {{-- Removed onsubmit="event.preventDefault(); showSuccessModal();" from here --}}
        <form id="joinKelasForm" onsubmit="event.preventDefault(); joinKelas();">
            <label for="kodeKelasInput">Masukkan Kode Kelas</label>
            <input type="text" id="kodeKelasInput" name="kode_kelas" placeholder="Kode Kelas" autocomplete="off" required>
            <div class="modal-actions">
                <button type="button" class="modal-btn cancel" onclick="closeJoinKelasModal()">Cancel</button>
                <button type="submit" class="modal-btn">Join</button> {{-- Removed onclick="joinKelas()" from here, as onsubmit handles it --}}
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="successModal">
    <div class="custom-modal success-modal">
        <div>Anda Telah Masuk Kelas!</div>
        <button class="modal-btn" onclick="closeSuccessModal()">OK</button>
    </div>
</div>

<div class="modal-overlay" id="errorModal">
    <div class="custom-modal error-modal">
        <div id="errorMessage">Terjadi kesalahan</div>
        <button class="modal-btn" onclick="closeErrorModal()">OK</button>
    </div>
</div>

<script>
    function openJoinKelasModal() {
        document.getElementById('joinKelasModal').classList.add('active');
        document.getElementById('kodeKelasInput').value = '';
        setTimeout(() => {
            document.getElementById('kodeKelasInput').focus();
        }, 200);
    }
    window.openJoinKelasModal = openJoinKelasModal;

    function closeJoinKelasModal() {
        document.getElementById('joinKelasModal').classList.remove('active');
    }
    window.closeJoinKelasModal = closeJoinKelasModal;

    function showSuccessModal() {
        closeJoinKelasModal();
        document.getElementById('successModal').classList.add('active');
    }
    window.showSuccessModal = showSuccessModal;

    function closeSuccessModal() {
        document.getElementById('successModal').classList.remove('active');
        window.location.reload();
    }
    window.closeSuccessModal = closeSuccessModal;

    function showErrorModal(message) {
        closeJoinKelasModal();
        document.getElementById('errorMessage').textContent = message;
        document.getElementById('errorModal').classList.add('active');
    }
    window.showErrorModal = showErrorModal;

    function closeErrorModal() {
        document.getElementById('errorModal').classList.remove('active');
    }
    window.closeErrorModal = closeErrorModal;

    function joinKelas() {
        const kodeKelas = document.getElementById('kodeKelasInput').value;
        if (!kodeKelas.trim()) {
            showErrorModal('Kode kelas tidak boleh kosong.');
            return;
        }
        const joinButton = document.querySelector('#joinKelasForm .modal-btn[type="submit"]');
        joinButton.disabled = true;
        fetch('{{ route("kelas-praktikum.enroll") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                kode_enroll: kodeKelas
            })
        })
        .then(response => {
            joinButton.disabled = false;
            if (response.success === false) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Terjadi kesalahan saat menghubungi server.');
                }).catch(() => {
                    throw new Error('Terjadi kesalahan jaringan atau server tidak merespons.');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showSuccessModal();
            } else {
                showErrorModal(data.message || 'Gagal mengikuti kelas.');
            }
        })
        .catch(error => {
            showErrorModal(error.message || 'Terjadi kesalahan tidak terduga.');
        });
    }
    window.joinKelas = joinKelas;
</script>

@endsection