@extends('layouts.app')

@section('title', 'Form Nilai Praktikan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/asprak.css') }}">
@endpush

@section('content')
<main class="container py-5">
    <h1 class="judul-halaman">Penilaian</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif        <div class="form-container">
            <form id="nilaiPraktikanForm" method="POST"
                action="{{ isset($nilaiPraktikan) ? route('informasi-nilai-asprak.update', [$kelas_id, $nilaiPraktikan->nilai_id]) : route('informasi-nilai-asprak.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if(isset($nilaiPraktikan))
                @method('PUT')
                @endif
                
                <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">

                <div class="form-group">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control input-judul" id="judul" name="judul"
                        value="{{ $nilaiPraktikan->judul ?? old('judul') }}" required>
                </div>

                <div class="form-group">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <div class="input-with-icon">
                        <input type="date" class="form-control input-tanggal" id="tanggal" name="tanggal"
                            value="{{ isset($nilaiPraktikan) && $nilaiPraktikan->tanggal ? $nilaiPraktikan->tanggal->format('Y-m-d') : old('tanggal') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control input-deskripsi" id="deskripsi" name="deskripsi" required>{{ isset($nilaiPraktikan) ? $nilaiPraktikan->deskripsi : old('deskripsi') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Tambah Media</label>
                    <div class="upload-card">
                        <label for="input-file-nilaiPraktikan" class="file-upload-label">
                            <img src="{{ asset('img/IconUpload.png') }}" alt="icon-upload" class="icon-upload">
                            Masukkan File
                            <input type="file" class="file-upload-input" id="input-file-nilaiPraktikan" name="upload_file" accept=".pdf">
                        </label>
                        <div class="file-info" id="input-file-nilaiPraktikan-info">
                            @if(isset($nilaiPraktikan) && $nilaiPraktikan->upload_file)
                            File saat ini: {{ basename($nilaiPraktikan->upload_file) }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="btn-submit-container">
                    <button type="submit" class="btn-submit">
                        {{ isset($nilaiPraktikan) ? 'Update' : 'Submit' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Modal Konfirmasi Update -->
        <div id="confirmModal" class="modal fade justify-content-center" aria-hidden="true">
            <div class="modal-content">
                <p>Apakah Anda Yakin Ingin {{ isset($nilaiPraktikan) ? 'Update' : 'Submit' }}?</p>
                <div class="modal-buttons">
                    <button id="cancelBtn">Tidak</button>
                    <button id="confirmBtn">Iya</button>
                </div>
            </div>
        </div>

        <!-- Modal Notifikasi Berhasil -->
        <div id="successModal" class="modal fade justify-content-center" aria-hidden="true">
            <div class="modal-content">
                <p>Nilai Praktikan Telah Berhasil {{ isset($nilaiPraktikan) ? 'Di Update!' : 'Di Tambahkan!' }}</p>
                <div class="modal-buttons">
                    <button id="okBtn">OK</button>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal Handling
            const form = document.getElementById('nilaiPraktikanForm');
            const confirmModal = document.getElementById('confirmModal');
            const successModal = document.getElementById('successModal');
            const successMessage = document.getElementById('successMessage');
            const cancelBtn = document.getElementById('cancelBtn');
            const confirmBtn = document.getElementById('confirmBtn');
            const okBtn = document.getElementById('okBtn');

            // Deteksi apakah ini halaman update atau create berdasarkan button text
            const submitButton = document.querySelector('.btn-submit');
            const isUpdateMode = submitButton.textContent.trim().toLowerCase() === 'update';

            // === FILE UPLOAD VALIDATION ===
            const fileInput = document.getElementById('input-file-nilaiPraktikan');
            const fileInfo = document.getElementById('input-file-nilaiPraktikan-info');
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    validateFile(e.target, 'input-file-nilaiPraktikan-info');
                });
            }

            function validateFile(input, infoId) {
                const file = input.files[0];
                const infoElement = document.getElementById(infoId);

                if (!file) {
                    if (isUpdateMode) {
                        infoElement.textContent = 'File saat ini: document.pdf';
                    } else {
                        infoElement.textContent = '';
                    }
                    return;
                }

                // Validasi ukuran
                if (file.size > maxSize) {
                    infoElement.textContent = 'File terlalu besar (max 2MB)';
                    infoElement.style.color = 'var(--error)';
                    input.value = '';
                } else {
                    infoElement.textContent = `File dipilih: ${file.name} (${formatFileSize(file.size)})`;
                    infoElement.style.color = 'var(--success)';
                }
            }

            function formatFileSize(bytes) {
                if (bytes < 1024) return bytes + ' bytes';
                else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                else return (bytes / 1048576).toFixed(1) + ' MB';
            }

            // === FORM VALIDATION ===
            function validateForm() {
                const judul = document.getElementById('judul').value.trim();
                const tanggal = document.getElementById('tanggal').value;
                const deskripsi = document.getElementById('deskripsi').value.trim();

                if (!judul || !tanggal || !deskripsi) {
                    alert('Semua field harus diisi!');
                    return false;
                }
                return true;
            }

            // === FORM SUBMISSION LOGIC ===
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!validateForm()) {
                        return;
                    }

                    // Tampilkan modal konfirmasi untuk kedua mode (create dan update)
                    confirmModal.style.display = 'flex';
                    confirmModal.style.opacity = '1';
                });
            }

            // === MODAL EVENT LISTENERS ===

            // Tombol "Tidak" pada modal konfirmasi
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    confirmModal.style.display = 'none';
                });
            }

            // Tombol "Iya" pada modal konfirmasi
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    confirmModal.style.display = 'none';
                    submitForm();
                });
            }

            // Tombol "OK" pada modal sukses
            if (okBtn) {
                okBtn.addEventListener('click', function() {
                    successModal.style.display = 'none';
                    window.location.href = "{{ route('informasi-nilai-asprak.index', $kelas_id) }}";
                });
            }

            // Tutup modal saat diklik di luar area modal
            window.addEventListener('click', function(event) {
                if (event.target === confirmModal) {
                    confirmModal.style.display = 'none';
                }
                if (event.target === successModal) {
                    successModal.style.display = 'none';
                }
            });

            // === SUBMIT FUNCTION ===
            function submitForm() {
                const submitButton = document.querySelector('.btn-submit');
                const originalText = submitButton.textContent;
                submitButton.textContent = 'Loading...';
                submitButton.disabled = true;

                const formData = new FormData(form);

                fetch(form.action, {
                    method: form.method || 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
                    },
                    body: formData
                })
                .then(async response => {
                    if (response.ok) {
                        // show success modal
                        successModal.style.display = 'flex';
                        successModal.style.opacity = '1';
                    } else if (response.status === 422) {
                        const data = await response.json();
                        let messages = [];
                        if (data.errors) {
                            Object.values(data.errors).forEach(arr => messages = messages.concat(arr));
                        }
                        alert(messages.join('\n') || 'Validasi gagal');
                    } else {
                        const text = await response.text();
                        alert('Terjadi kesalahan: ' + text);
                    }
                })
                .catch(err => {
                    alert('Terjadi kesalahan: ' + err.message);
                })
                .finally(() => {
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                });
            }
        });
    </script>
@endpush