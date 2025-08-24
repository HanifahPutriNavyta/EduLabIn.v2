@extends('layouts.app')

@section('title', 'Form Absensi Praktikan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/asprak.css') }}">
<style>
.form-control-static {
    padding: 12px 16px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    color: #495057;
    font-weight: 500;
}
</style>
@endpush

@section('content')
<main class="container py-5">
        <h1 class="judul-halaman">Absensi Praktikan</h1>

        <div class="form-container">
            <form id="absensiForm" method="POST"
                action="{{ isset($absensi) ? route('absensi-praktikan.update', $absensi->absensi_id) : route('absensi-praktikan.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if(isset($absensi))
                @method('PUT')
                @endif

                <div class="form-group">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control input-judul" id="judul" name="judul"
                        value="{{ $absensi->judul ?? old('judul') }}" required>
                    @error('judul')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kelas Praktikum</label>
                    <!-- Kelas sudah dipilih dari halaman sebelumnya - hidden input -->
                    <input type="hidden" name="kelas_id" value="{{ $kelas->kelas_id }}">
                    <div class="form-control-static">
                        <strong>{{ $kelas->mataKuliah->nama_mk }} - {{ $kelas->kode_kelas }}</strong>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <div class="input-with-icon">
                        <input type="date" class="form-control input-tanggal" id="tanggal" name="tanggal"
                            value="{{ $absensi->tanggal ?? old('tanggal') }}" required>
                        </input>
                    </div>
                    @error('tanggal')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control input-deskripsi" id="deskripsi" name="deskripsi">{{ $absensi->deskripsi ?? old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tambah Media</label>
                    <div class="upload-card">
                        <label for="upload_file" class="file-upload-label">
                            <img src="{{ asset('img/IconUpload.png') }}" alt="icon-upload" class="icon-upload">
                            Masukkan File
                            <input type="file" class="file-upload-input" id="upload_file" name="upload_file" 
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </label>
                        <div class="file-info" id="upload_file-info">
                            @if(isset($absensi->upload_file))
                            File saat ini: {{ basename($absensi->upload_file) }}
                            @endif
                        </div>
                    </div>
                    @error('upload_file')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="btn-submit-container">
                    <button type="submit" class="btn-submit">
                        {{ isset($absensi) ? 'Update' : 'Submit' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Modal Konfirmasi Update -->
        <div id="confirmModal" class="modal fade justify-content-center" aria-hidden="true">
            <div class="modal-content">
                <p>Apakah Anda Yakin Ingin {{ isset($absensi) ? 'Update' : 'Submit' }}?</p>
                <div class="modal-buttons">
                    <button id="cancelBtn">Tidak</button>
                    <button id="confirmBtn">Iya</button>
                </div>
            </div>
        </div>

        <!-- Modal Notifikasi Berhasil -->
        <div id="successModal" class="modal fade justify-content-center" aria-hidden="true">
            <div class="modal-content">
                <p>Absensi Telah Berhasil {{ isset($absensi) ? 'Di Update!' : 'Di Tambahkan!' }}</p>
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
            const form = document.getElementById('absensiForm');
            const confirmModal = document.getElementById('confirmModal');
            const successModal = document.getElementById('successModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const confirmBtn = document.getElementById('confirmBtn');
            const okBtn = document.getElementById('okBtn');

            // Deteksi apakah ini halaman update atau create berdasarkan button text
            const submitButton = document.querySelector('.btn-submit');
            const isUpdateMode = submitButton.textContent.trim().toLowerCase() === 'update';

            // === FILE UPLOAD VALIDATION ===
            const fileInput = document.getElementById('upload_file');
            const fileInfo = document.getElementById('upload_file-info');
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    validateFile(e.target, 'upload_file-info');
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

                if (!judul) {
                    alert('Judul harus diisi!');
                    return false;
                }
                if (!tanggal) {
                    alert('Tanggal harus diisi!');
                    return false;
                }
                if (!deskripsi) {
                    alert('Deskripsi harus diisi!');
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
                    window.location.href = "{{ route('absensi-praktikan.index', $kelas->kelas_id) }}";
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
                // Submit form via AJAX, show success modal, redirect only after OK
                const submitButton = document.querySelector('.btn-submit');
                const originalText = submitButton.textContent;
                submitButton.textContent = 'Loading...';
                submitButton.disabled = true;
                const formData = new FormData(form);
                fetch(form.action, {
                    method: form.method,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        successModal.style.display = 'flex';
                        successModal.style.opacity = '1';
                        submitButton.textContent = originalText;
                        submitButton.disabled = false;
                    } else {
                        return response.text().then(text => { throw new Error(text); });
                    }
                })
                .catch(error => {
                    alert('Terjadi kesalahan: ' + error.message);
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                });
            }
        });
    </script>
@endpush