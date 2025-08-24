@extends('layouts.app')

@section('title', 'Tambah Data Dosen Praktikum')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/data-dosen.css') }}">
@endpush


@section('content')
<main class="container mt-4 mb-5">
    <h1 class="judul-halaman">Tambah Data Dosen Praktikum</h1>

    <div class="line-div"></div>

    <form id="addClassForm">
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" required>
        </div>
        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control" id="nip" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" required>
        </div>
        {{-- Mata Kuliah --}}
        <div class="mb-3">
            <label for="course" class="form-label">Mata Kuliah</label>
            <select class="form-select" id="course" required>
                <option value="">Pilih Mata Kuliah</option>
                @foreach($mataKuliahs as $mk)
                    <option value="{{ $mk->mk_id }}">{{ $mk->nama_mk }}</option>
                @endforeach
            </select>
        </div>
        {{-- Kelas --}}
        <div class="mb-3">
            <label for="class" class="form-label">Kelas</label>
            <select class="form-select" id="class" required disabled>
                <option value="">Pilih Kelas</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->kelas_id }}" data-mk="{{ $kelas->mk_id }}">{{ $kelas->kode_kelas }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="studyProgram" class="form-label">Progam Studi</label>
            <input type="text" class="form-control" id="studyProgram" required>
        </div>
        <div class="mb-3">
            <label for="faculty" class="form-label">Fakultas</label>
            <input type="text" class="form-control" id="faculty" required>
        </div>
        <div class="mb-3">
            <label for="departement" class="form-label">Departemen</label>
            <input type="text" class="form-control" id="departement" required>
        </div>

        <div class="mb-3 d-flex justify-content-center">
            <div class="upload-container ">
                <div class="upload-header">Upload Foto Diri</div>
                <label for="fotoDiri" class="upload-box" id="uploadBox">
                    <span id="uploadIcon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                    </span>
                    <img id="previewImage" src="#" alt="Preview" style="display:none; max-height:100px; max-width:100%; object-fit:contain; border-radius:8px;" />
                    <button type="button" id="removeImageBtn" style="display:none; position:absolute; top:8px; right:8px; background:#fff; border:none; border-radius:50%; box-shadow:0 2px 6px rgba(0,0,0,0.15); width:24px; height:24px; cursor:pointer; align-items:center; justify-content:center; padding:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                    <input type="file" id="fotoDiri" name="fotoDiri" accept="image/*" style="display:none;">
                </label>
            </div>
        </div>

        <div class="btn-submit-container">
            <button type="submit" class="btn-submit">Submit</button>
        </div>
    </form>
</main>

<!-- Modal Konfirmasi Submit -->
<div id="confirmModal" class="modal fade justify-content-center" aria-hidden="true">
    <div class="modal-content">
        <p>Apakah Anda Yakin Ingin Submit?</p>
        <div class="modal-buttons">
            <button id="cancelBtn">Tidak</button>
            <button id="confirmSubmitBtn">Iya</button>
        </div>
    </div>
</div>

<!-- Modal Notifikasi Berhasil -->
<div id="successModal" class="modal fade justify-content-center" aria-hidden="true">
    <div class="modal-content">
        <p>Data dosen berhasil ditambahkan!</p>
        <div class="modal-buttons">
            <button id="okBtn">OK</button>
        </div>
    </div>
</div>

<!-- Modal Error -->
<div id="errorModal" class="modal fade justify-content-center" aria-hidden="true">
    <div class="modal-content">
        <p id="errorMessage">Terjadi kesalahan saat menyimpan data.</p>
        <div class="modal-buttons">
            <button id="errorOkBtn">OK</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .judul-halaman {
        font-size: 20px;
        font-weight: 600;
        color: var(--black);
        margin-bottom: 20px;
    }

    .upload-container {
        position: relative;
        width: 100%;
        max-width: 400px;
        margin-top: 16px;
        border: 2px solid #ddd;
        border-radius: 10px;
        padding: 0;
        background: #fff;
    }
    .upload-header {
        background: var(--secondary, orange);
        color: #fff;
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        font-weight: 600;
        padding: 8px 16px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
    .upload-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 120px;
        cursor: pointer;
        background: #fff;
        position: relative;
        overflow: hidden;
    }
    .upload-box img {
        margin-top: 12px;
    }
    #removeImageBtn {
        display: flex;
    }

    .btn-submit-container {
        display: flex;
        justify-content: center;
        margin-top: 2.5rem;
    }

    .btn-submit {
        width: 100px;
        height: 40px;
        margin-top: 50px;
        background-color: var(--secondary);
        border: 1px solid var(--secondary-orange800);
        border-radius: 8px;
        font-weight: 600;
        font-size: 16px;
        color: var(--primary);
        cursor: pointer;
        justify-content: center;
        text-align: center;
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    }

    .btn-submit:hover {
        opacity: 0.9;
    }

    .form-label {
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    .form-control,
    .form-control:focus {
        box-shadow: none;
        border: 1px solid #ddd;
    }

    .btn {
        background-color: var(--secondary);
        color: white;
        width: 100%;
        margin-top: 24px;
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        font-weight: 500;
        min-width: 80px;
    }

    .btn:hover {
        background-color: var(--secondary-orange800);
        color: white;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background-color: white;
        margin: auto;
        padding: 15px;
        border-radius: 6px;
        width: auto;
        min-width: 180px;
        max-width: 280px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .modal-content p {
        margin-bottom: 12px;
        font-family: 'Montserrat', sans-serif;
        font-size: 13px;
        color: #333;
        line-height: 1.3;
    }

    .modal-content p:not(#errorMessage) {
        white-space: nowrap;
    }

    .modal-buttons {
        display: flex;
        gap: 6px;
        justify-content: center;
    }

    .modal-buttons button {
        padding: 5px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
        font-size: 11px;
        font-weight: 500;
        min-width: 50px;
    }

    #cancelBtn {
        background-color: #6c757d;
        color: white;
    }

    #confirmSubmitBtn, #confirmBtn {
        background-color: var(--secondary);
        color: white;
    }

    #okBtn, #errorOkBtn {
        background-color: var(--secondary);
        color: white;
    }

    .modal-buttons button:hover {
        opacity: 0.9;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const confirmModal = document.getElementById('confirmModal');
    const successModal = document.getElementById('successModal');
    const errorModal = document.getElementById('errorModal');
    
    let formDataToSubmit = null;

    // Modal helper functions
    function showModal(modal) {
        modal.classList.add('show');
    }

    function hideModal(modal) {
        modal.classList.remove('show');
    }

    document.getElementById('fotoDiri').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('previewImage');
        const icon = document.getElementById('uploadIcon');
        const removeBtn = document.getElementById('removeImageBtn');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                icon.style.display = 'none';
                removeBtn.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
            icon.style.display = 'block';
            removeBtn.style.display = 'none';
        }
    });

    document.getElementById('removeImageBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const fileInput = document.getElementById('fotoDiri');
        const preview = document.getElementById('previewImage');
        const icon = document.getElementById('uploadIcon');
        const removeBtn = document.getElementById('removeImageBtn');
        fileInput.value = '';
        preview.src = '#';
        preview.style.display = 'none';
        icon.style.display = 'block';
        removeBtn.style.display = 'none';
    });

    // Form submit - show confirmation modal
    document.getElementById('addClassForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Prepare form data
        formDataToSubmit = new FormData();
        formDataToSubmit.append('nama', document.getElementById('name').value);
        formDataToSubmit.append('nidn', document.getElementById('nip').value);
        formDataToSubmit.append('email', document.getElementById('email').value);
        formDataToSubmit.append('mata_kuliah_id', document.getElementById('course').value);
        formDataToSubmit.append('kelas_id', document.getElementById('class').value);
        formDataToSubmit.append('program_studi', document.getElementById('studyProgram').value);
        formDataToSubmit.append('fakultas', document.getElementById('faculty').value);
        formDataToSubmit.append('departemen', document.getElementById('departement').value);

        const foto = document.getElementById('fotoDiri').files[0];
        if (foto) {
            formDataToSubmit.append('foto', foto);
        }

        // Show confirmation modal
        showModal(confirmModal);
    });

    // Confirm submit button
    document.getElementById('confirmSubmitBtn').addEventListener('click', async function() {
        hideModal(confirmModal);
        
        try {
            const response = await fetch("{{ route('data-dosen.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formDataToSubmit
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showModal(successModal);
            } else {
                document.getElementById('errorMessage').textContent = result.message || 'Gagal menambahkan data dosen.';
                showModal(errorModal);
            }
        } catch (error) {
            document.getElementById('errorMessage').textContent = 'Terjadi kesalahan saat mengirim data.';
            showModal(errorModal);
            console.error(error);
        }
    });

    // Cancel button
    document.getElementById('cancelBtn').addEventListener('click', function() {
        hideModal(confirmModal);
    });

    // Success modal OK button
    document.getElementById('okBtn').addEventListener('click', function() {
        hideModal(successModal);
        window.location.href = "{{ route('data-dosen.index') }}";
    });

    // Error modal OK button
    document.getElementById('errorOkBtn').addEventListener('click', function() {
        hideModal(errorModal);
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === confirmModal) {
            hideModal(confirmModal);
        }
        if (event.target === successModal) {
            hideModal(successModal);
        }
        if (event.target === errorModal) {
            hideModal(errorModal);
        }
    });

    document.getElementById('course').addEventListener('change', function() {
        const selectedMk = this.value;
        const classSelect = document.getElementById('class');
        const options = classSelect.querySelectorAll('option');

        // Enable/disable select kelas
        if (selectedMk) {
            classSelect.disabled = false;
        } else {
            classSelect.disabled = true;
            classSelect.value = '';
        }

        options.forEach(option => {
            if (!option.value) {
                option.style.display = '';
                option.disabled = false;
                return;
            }
            if (option.getAttribute('data-mk') === selectedMk) {
                option.style.display = '';
                option.disabled = false;
            } else {
                option.style.display = 'none';
                option.disabled = true;
            }
        });

        // Reset pilihan kelas jika tidak cocok
        classSelect.value = '';
    });
});
</script>
@endpush
