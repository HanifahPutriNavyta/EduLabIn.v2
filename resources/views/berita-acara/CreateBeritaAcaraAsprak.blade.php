<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Berita Acara</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/asprak.css') }}" rel="stylesheet">

</head>

<body>
    @include('partials.navbar')
    <main class="container py-5">
        <h1 class="judul-halaman">Berita Acara</h1>

        <div class="form-container">
            <!-- Display Laravel validation errors -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Display success message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Display error message -->
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form id="beritaAcaraForm" method="POST" action="{{ $submitUrl }}" enctype="multipart/form-data">
                @csrf
                @if(isset($submitText) && $submitText === 'Update')
                @method('PUT')
                @endif
                <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">

                <div class="form-group">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control input-judul @error('judul') is-invalid @enderror" id="judul" name="judul"
                        value="{{ $beritaAcara->judul ?? old('judul') }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <div class="input-with-icon">
                        <input type="date" class="form-control input-tanggal @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal"
                            value="{{ $beritaAcara->tanggal_kegiatan ?? old('tanggal') }}" required>
                        </input>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="tipe_pertemuan" class="form-label">Tipe Pertemuan</label>
                    <select class="form-control input-tipe-pertemuan @error('tipe_pertemuan') is-invalid @enderror" id="tipe_pertemuan" name="tipe_pertemuan" required>
                        <option value="">Pilih Tipe Pertemuan</option>
                        <option value="Luring" {{ (isset($beritaAcara) && $beritaAcara->tipe_pertemuan == 'luring') || old('tipe_pertemuan') == 'Luring' ? 'selected' : '' }}>Luring</option>
                        <option value="Daring" {{ (isset($beritaAcara) && $beritaAcara->tipe_pertemuan == 'daring') || old('tipe_pertemuan') == 'Daring' ? 'selected' : '' }}>Daring</option>
                    </select>
                    @error('tipe_pertemuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Upload Berita Acara</label>
                    <div class="upload-card">
                        <label for="input-file-beritaAcara" class="file-upload-label">
                            <img src="{{ asset('img/IconUpload.png') }}" alt="icon-upload" class="icon-upload">
                            Masukkan File
                            <input type="file" class="file-upload-input @error('file-input-beritaAcara') is-invalid @enderror" id="input-file-beritaAcara" name="file-input-beritaAcara" accept=".pdf" required>
                        </label>
                        <div class="file-info" id="input-file-beritaAcara-info">
                            @if(isset($beritaAcara) && isset($beritaAcara->upload_berita_acara))
                            File saat ini: {{ basename($beritaAcara->upload_berita_acara) }}
                            @endif
                        </div>
                        @error('file-input-beritaAcara')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group" id="uploadContainer" style="display: none;">
                    <label class="form-label">Upload Bukti Pertemuan</label>
                    <div class="upload-card">
                        <label for="input-file-buktiPertemuan" class="file-upload-label">
                            <img src="{{ asset('img/IconUpload.png') }}" alt="icon-upload" class="icon-upload">
                            Masukkan File
                            <input type="file" class="file-upload-input @error('file-input-buktiPertemuan') is-invalid @enderror" id="input-file-buktiPertemuan" name="file-input-buktiPertemuan" accept=".pdf,.jpg,.jpeg,.png">
                        </label>
                        <div class="file-info" id="input-file-buktiPertemuan-info">
                            @if(isset($beritaAcara) && isset($beritaAcara->upload_bukti_pertemuan))
                            File saat ini: {{ basename($beritaAcara->upload_bukti_pertemuan) }}
                            @endif
                        </div>
                        @error('file-input-buktiPertemuan')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="btn-submit-container">
                    <button type="submit" class="btn-submit">
                        {{ isset($submitText) ? $submitText : (isset($beritaAcara) ? 'Update' : 'Submit') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Modal Konfirmasi Update -->
        <div id="confirmModal" class="modal fade justify-content-center" aria-hidden="true">
            <div class="modal-content">
                <p>Apakah Anda Yakin Ingin {{ isset($beritaAcara) ? 'Update' : 'Submit' }}?</p>
                <div class="modal-buttons">
                    <button id="cancelBtn">Tidak</button>
                    <button id="confirmBtn">Iya</button>
                </div>
            </div>
        </div>

        <!-- Modal Notifikasi Berhasil -->
        <div id="successModal" class="modal fade justify-content-center" aria-hidden="true">
            <div class="modal-content">
                <p>Berita Acara Telah Berhasil {{ isset($beritaAcara) ? 'Di Update!' : 'Di Tambahkan!' }}</p>
                <div class="modal-buttons">
                    <button id="okBtn">OK</button>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal Handling
            const form = document.getElementById('beritaAcaraForm');
            const confirmModal = document.getElementById('confirmModal');
            const successModal = document.getElementById('successModal');
            const successMessage = document.getElementById('successMessage');
            const cancelBtn = document.getElementById('cancelBtn');
            const confirmBtn = document.getElementById('confirmBtn');
            const okBtn = document.getElementById('okBtn');

            // Deteksi apakah ini halaman update atau create berdasarkan button text
            const submitButton = document.querySelector('.btn-submit');
            const isUpdateMode = submitButton.textContent.trim().toLowerCase() === 'update';

            // === CONDITIONAL DISPLAY FOR BUKTI PERTEMUAN ===
            const tipePertemuanSelect = document.getElementById('tipe_pertemuan');
            const uploadContainer = document.getElementById('uploadContainer');

            // Function to toggle bukti pertemuan visibility
            function toggleBuktiPertemuan() {
                if (tipePertemuanSelect.value === 'Daring') {
                    uploadContainer.style.display = 'block';
                } else {
                    uploadContainer.style.display = 'none';
                    // Reset file input and info when hidden
                    const buktiPertemuanInput = document.getElementById('input-file-buktiPertemuan');
                    if (buktiPertemuanInput) {
                        buktiPertemuanInput.value = '';
                        const buktiInfo = document.getElementById('input-file-buktiPertemuan-info');
                        if (buktiInfo) {
                            buktiInfo.textContent = '';
                        }
                    }
                }
            }

            // Initial check on page load
            toggleBuktiPertemuan();

            // Listen for changes in tipe pertemuan dropdown
            if (tipePertemuanSelect) {
                tipePertemuanSelect.addEventListener('change', toggleBuktiPertemuan);
            }

            // === FILE UPLOAD VALIDATION ===
            const fileInputBerita = document.getElementById('input-file-beritaAcara');
            const fileInputBukti = document.getElementById('input-file-buktiPertemuan');
            const fileInfoBerita = document.getElementById('input-file-beritaAcara-info');
            const fileInfoBukti = document.getElementById('input-file-buktiPertemuan-info');
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (fileInputBerita) {
                fileInputBerita.addEventListener('change', function(e) {
                    validateFile(e.target, 'input-file-beritaAcara-info');
                });
            }

            if (fileInputBukti) {
                fileInputBukti.addEventListener('change', function(e) {
                    validateFile(e.target, 'input-file-buktiPertemuan-info');
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
                const tipePertemuan = document.getElementById('tipe_pertemuan').value;
                const beritaAcaraFile = document.getElementById('input-file-beritaAcara').files[0];

                if (!judul || !tanggal || !tipePertemuan) {
                    alert('Semua field harus diisi!');
                    return false;
                }

                // Validasi file berita acara
                if (!beritaAcaraFile && !isUpdateMode) {
                    alert('File berita acara harus diupload!');
                    return false;
                }

                // Validasi khusus untuk tipe pertemuan Daring
                if (tipePertemuan === 'Daring') {
                    const buktiPertemuanFile = document.getElementById('input-file-buktiPertemuan').files[0];
                    if (!buktiPertemuanFile && !isUpdateMode) {
                        alert('Upload bukti pertemuan diperlukan untuk pertemuan Daring!');
                        return false;
                    }
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
                    window.location.href = "{{ route('berita-acara.indexAsprak', $kelas_id) }}";
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
                // Submit the form via AJAX, show success modal, redirect only after OK
                confirmModal.style.display = 'none';
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
</body>

</html>