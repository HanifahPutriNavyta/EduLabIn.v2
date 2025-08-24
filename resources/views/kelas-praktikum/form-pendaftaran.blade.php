@extends('layouts.app')
@section('title', 'Form Pendaftaran')
@push('styles')
<link href="{{ asset('css/casprak.css') }}" rel="stylesheet">
@endpush
@section('content')
<h1 class="judul-halaman">Form Pendaftaran</span></h1>
        <div class="form-container">

            <form id="pendaftaranForm" action="{{ route('calonAsprak.SubmitPendaftaran') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="matkul_id" value="{{ $matkul->mk_id }}">

                <div class="form-section">
                    <div class="form-group">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required placeholder="Nama">
                    </div>

                    <div class="form-group">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim" required placeholder="Nim" maxlength="15">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Email">
                    </div>

                    <div class="form-group">
                        <label for="whatsapp" class="form-label">No. Whatsapp</label>
                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" required placeholder="No. Whatsapp" maxlength="15">
                    </div>

                    <div class="form-group">
                        <label for="prodi" class="form-label">Program Studi</label>
                        <input type="text" class="form-control" id="prodi" name="prodi" required placeholder="Program Studi">
                    </div>

                    <div class="form-group">
                        <label for="fakultas" class="form-label">Fakultas</label>
                        <input type="text" class="form-control" id="fakultas" name="fakultas" required placeholder="Fakultas">
                    </div>

                    <div class="form-group">
                        <label for="angkatan" class="form-label">Angkatan</label>
                        <input type="text" class="form-control" id="angkatan" name="angkatan" required placeholder="Angkatan" maxlength="4">
                    </div>

                    <div class="form-group">
                        <label for="status-akademik" class="form-label">Status Akademik</label>
                        <input type="text" class="form-control" id="status-akademik" name="status" required placeholder="Status Akademik">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mata Kuliah yang Dipilih</label>
                    <input type="text" class="form-control readonly-field" value="{{ $matkul->nama_mk }}" readonly>
                </div>

                <!-- Upload Bukti -->
                <div class="upload-card">
                    <div class="card-header">Upload Bukti</div>
                    <div class="card-body">
                        <div class="card-detail">
                            <label for="bukti" class="file-upload-label">
                                <img src="{{ asset('img/IconUpload.png') }}" alt="icon-upload" class="icon-upload">
                                <input type="file" class="form-control file-upload-input" id="bukti" name="bukti" accept=".pdf" required>
                            </label>
                            <div class="file-info" id="bukti-info"></div>
                        </div>
                    </div>
                </div>

                <!-- Upload Foto Diri -->
                <div class="upload-card">
                    <div class="card-header">Upload Foto Diri</div>
                    <div class="card-body">
                        <div class="card-detail">
                            <label for="foto" class="file-upload-label">
                                <img src="{{ asset('img/IconUpload.png') }}" alt="icon-upload" class="icon-upload">
                                <input type="file" class="form-control file-upload-input" id="foto" name="foto-diri" accept=".pdf,.jpg,.jpeg,.png" required>
                            </label>
                            <div class="file-info" id="foto-info"></div>
                        </div>
                    </div>
                </div>

                <div class="btn-submit-container">
                    <button type="submit" class="btn-submit">Submit</button>
                </div>

            </form>
        </div>

        <!-- Modal Konfirmasi Submit -->
        <div id="confirmModal" class="modal fade justify-content-center" aria-hidden="true">
            <div class="modal-content">
                <p>Apakah Anda Yakin Ingin Submit?</p>
                <div class="modal-buttons">
                    <button id="cancelBtn">Tidak</button>
                    <button id="confirmBtn">Iya</button>
                </div>
            </div>
        </div>

        <!-- Modal Notifikasi Berhasil -->
        <div id="successModal" class="modal fade justify-content-center" aria-hidden="true">
            <div class="modal-content">
                <p>Form Anda Telah Berhasil Di Simpan!</p>
                <div class="modal-buttons">
                    <button id="okBtn">OK</button>
                </div>
            </div>
        </div>

        <!-- Modal Error -->
        <div id="errorModal" class="modal fade justify-content-center" aria-hidden="true">
            <div class="modal-content">
                <p id="errorMessage">Terjadi kesalahan!</p>
                <div class="modal-buttons">
                    <button id="errorOkBtn">OK</button>
                </div>
            </div>
        </div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-format input angka
            document.getElementById('nim').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 15);
            });

            document.getElementById('whatsapp').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 15);
            });

            document.getElementById('angkatan').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 4);
            });

            // Validasi file upload
            const buktiInput = document.getElementById('bukti');
            const fotoInput = document.getElementById('foto');
            const maxSize = 2 * 1024 * 1024; // 2MB

            buktiInput.addEventListener('change', function(e) {
                validateFile(e.target, 'bukti-info');
            });

            fotoInput.addEventListener('change', function(e) {
                validateFile(e.target, 'foto-info');
            });

            function validateFile(input, infoId) {
                const file = input.files[0];
                const infoElement = document.getElementById(infoId);

                if (!file) {
                    infoElement.textContent = '';
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

            // Validasi Form
            function validateForm() {
                let isValid = true;

                // Validasi NIM
                const nim = document.getElementById('nim');
                if (!/^\d+$/.test(nim.value)) {
                    alert('NIM harus berupa angka');
                    nim.focus();
                    isValid = false;
                }

                // Validasi WhatsApp
                const whatsapp = document.getElementById('whatsapp');
                if (!/^\d+$/.test(whatsapp.value)) {
                    alert('Nomor WhatsApp harus berupa angka');
                    whatsapp.focus();
                    isValid = false;
                }

                // Validasi Angkatan
                const angkatan = document.getElementById('angkatan');
                if (!/^\d{4}$/.test(angkatan.value)) {
                    alert('Angkatan harus 4 digit angka (Contoh: 2022)');
                    angkatan.focus();
                    isValid = false;
                }
                return isValid;
            }

            // Modal handling
            const form = document.getElementById('pendaftaranForm');
            const confirmModal = document.getElementById('confirmModal');
            const successModal = document.getElementById('successModal');
            const errorModal = document.getElementById('errorModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const confirmBtn = document.getElementById('confirmBtn');
            const okBtn = document.getElementById('okBtn');
            const errorOkBtn = document.getElementById('errorOkBtn');

            // Saat form di-submit, tampilkan modal konfirmasi
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (validateForm()) {
                    confirmModal.style.display = 'flex';
                    confirmModal.style.opacity = '1';
                }
            });

            // Tombol "Tidak" pada modal konfirmasi
            cancelBtn.addEventListener('click', function() {
                confirmModal.style.display = 'none';
            });

            // Tombol "Ya" pada modal konfirmasi
            confirmBtn.addEventListener('click', function() {
                confirmModal.style.display = 'none';

                // Kirim form secara asynchronous
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {
                    // try to parse JSON, but handle non-JSON responses gracefully
                    let data = null;
                    try {
                        data = await response.json();
                    } catch (e) {
                        data = null;
                    }

                    if (!response.ok) {
                        // server returned an error (validation or server error)
                        let message = 'Terjadi kesalahan saat mengirim form';
                        if (data) {
                            if (data.message) message = data.message;
                            else if (data.errors) message = Object.values(data.errors).flat().join('\n');
                        }
                        document.getElementById('errorMessage').textContent = message;
                        errorModal.style.display = 'flex';
                        errorModal.style.opacity = '1';
                        return;
                    }

                    // success path
                    if (data && data.success) {
                        successModal.style.display = 'flex';
                        successModal.style.opacity = '1';
                    } else {
                        document.getElementById('errorMessage').textContent = (data && data.message) ? data.message : 'Terjadi kesalahan saat mengirim form';
                        errorModal.style.display = 'flex';
                        errorModal.style.opacity = '1';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('errorMessage').textContent = 'Terjadi kesalahan saat mengirim form';
                    errorModal.style.display = 'flex';
                    errorModal.style.opacity = '1';
                });
            });

            // Tombol "OK" pada modal sukses
            okBtn.addEventListener('click', function() {
                successModal.style.display = 'none';
                window.location.href = '{{ route("calonAsprak.DashboardCasprak") }}';
            });

            // Tombol "OK" pada modal error
            errorOkBtn.addEventListener('click', function() {
                errorModal.style.display = 'none';
            });
        });
    </script>
@endpush