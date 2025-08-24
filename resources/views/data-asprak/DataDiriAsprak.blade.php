@extends('layouts.app')

@section('title', 'Data Diri Asisten Praktikum')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/asprak.css') }}">
@endpush

@section('content')
<main class="container mt-4 mb-5">
    <h1 class="judul-halaman">Form Data Diri Asisten Praktikum</h1>
    
    <div class="line-div"></div>

    <!-- Info Kelas -->
    <div class="alert alert-info mb-4">
        <strong>Kelas:</strong> {{ $kelas->kode_kelas }} - {{ $kelas->mataKuliah->nama_mk ?? 'N/A' }}
    </div>

    <div class="form-container">
        <form id="dataDiriAsprak" action="{{ route('data-diri-asprak.updateData', $kelas_id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control input-nama" id="nama" name="nama" 
                       placeholder="Nama" value="{{ auth()->user()->profil->nama_lengkap ?? '' }}"  disabled >
            </div>

            <div class="form-group">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" class="form-control input-nim" id="nim" name="nim" 
                       placeholder="NIM" value="{{ auth()->user()->profil->no_identitas ?? '' }}" disabled >
            </div>

            <div class="form-group">
                <label for="jumlah_mahasiswa" class="form-label">Jumlah Mahasiswa di Kelas</label>
                <input type="number" class="form-control input-jumlahMahasiswa" id="jumlah_mahasiswa" name="jumlah_mahasiswa" 
                       placeholder="Jumlah Mahasiswa di Kelas" value="{{ $dataDiri->jumlah_mahasiswa ?? '' }}" required>
            </div>

            <div class="form-group">
                <label for="nomor_whatsapp" class="form-label">No. WA</label>
                <input type="text" class="form-control input-noWA" id="nomor_whatsapp" name="nomor_whatsapp" 
                       placeholder="No. WA" value="{{ $dataDiri->nomor_whatsapp ?? '' }}" required>
            </div>                

            <div class="form-group">
                <label for="nomor_ktp" class="form-label">No. KTP</label>
                <input type="text" class="form-control input-noKTP" id="nomor_ktp" name="nomor_ktp" 
                       placeholder="No. KTP" value="{{ $dataDiri->nomor_ktp ?? '' }}" required>
            </div>

            <div class="form-group">
                <label for="nomor_rekening" class="form-label">No. Rek (Nama Bank)</label>
                <input type="text" class="form-control input-noRek" id="nomor_rekening" name="nomor_rekening" 
                       placeholder="No. Rek (Nama Bank)" value="{{ $dataDiri->nomor_rekening ?? '' }}" required>
            </div>

            <div class="btn-submit-container">
                <button type="submit" class="btn-submit">
                    {{ $dataDiri ? 'Update' : 'Submit' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Modal Konfirmasi Submit -->
    <div id="confirmModal" class="modal fade justify-content-center" aria-hidden="true">
        <div class="modal-content">
            <p>Apakah Anda Yakin Ingin {{ $dataDiri ? 'Update' : 'Submit' }}?</p>
            <div class="modal-buttons">
                <button id="cancelBtn">Tidak</button>
                <button id="confirmBtn">Iya</button>
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi Berhasil -->
    <div id="successModal" class="modal fade justify-content-center" aria-hidden="true">
        <div class="modal-content">
            <p>Data Telah Berhasil Disimpan!</p>
            <div class="modal-buttons">
                <button id="okBtn">OK</button>
            </div>
        </div>
    </div>
</main>
@endsection

@push('styles')
<style>
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

    #confirmBtn {
        background-color: var(--secondary);
        color: white;
    }

    #okBtn {
        background-color: var(--secondary);
        color: white;
    }

    .modal-buttons button:hover {
        opacity: 0.9;
    }

    .alert {
        padding: 12px 16px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-info {
        color: #31708f;
        background-color: #d9edf7;
        border-color: #bce8f1;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get modal elements
        const confirmModal = document.getElementById('confirmModal');
        const successModal = document.getElementById('successModal');

        // Modal helper functions
        function showModal(modal) {
            modal.classList.add('show');
        }

        function hideModal(modal) {
            modal.classList.remove('show');
        }

            // Auto-format input angka
            document.getElementById('nim').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 15);
            });

            document.getElementById('nomor_whatsapp').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 15);
            });

            document.getElementById('nomor_ktp').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 16);
            });

            // Validasi Form
            function validateForm() {
                let isValid = true;

                // Validasi NIM
                const nim = document.getElementById('nim');
                if (!/^\d+$/.test(nim.value)) {
                    alert('NIM harus berupa angka');
                    nim.focus();
                    return false;
                }

                // Validasi WhatsApp
                const whatsapp = document.getElementById('nomor_whatsapp');
                if (!/^\d+$/.test(whatsapp.value)) {
                    alert('Nomor WhatsApp harus berupa angka');
                    whatsapp.focus();
                    return false;
                }

                // Validasi KTP
                const ktp = document.getElementById('nomor_ktp');
                if (!/^\d+$/.test(ktp.value)) {
                    alert('Nomor KTP harus berupa angka');
                    ktp.focus();
                    return false;
                }

                // Validasi Jumlah Mahasiswa
                const jumlahMahasiswa = document.getElementById('jumlah_mahasiswa');
                if (!/^\d+$/.test(jumlahMahasiswa.value) || parseInt(jumlahMahasiswa.value) <= 0) {
                    alert('Jumlah mahasiswa harus berupa angka positif');
                    jumlahMahasiswa.focus();
                    return false;
                }

                return true;
            }

            // Modal handling
            const form = document.getElementById('dataDiriAsprak');
            const cancelBtn = document.getElementById('cancelBtn');
            const confirmBtn = document.getElementById('confirmBtn');
            const okBtn = document.getElementById('okBtn');

            // Saat form di-submit, tampilkan modal konfirmasi
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (validateForm()) {
                    showModal(confirmModal);
                }
            });

            // Tombol "Tidak" pada modal konfirmasi
            cancelBtn.addEventListener('click', function() {
                hideModal(confirmModal);
            });

            // Tombol "Ya" pada modal konfirmasi
            confirmBtn.addEventListener('click', function() {
                hideModal(confirmModal);

                // Kirim form secara asynchronous
                fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Tampilkan modal sukses setelah submit berhasil
                            showModal(successModal);
                        } else {
                            throw new Error('Submit gagal');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengirim form');
                    });
            });

            // Tombol "OK" pada modal sukses
            okBtn.addEventListener('click', function() {
                hideModal(successModal);
                // Reload page to show updated data
                window.location.reload();
            });

            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === confirmModal) {
                    hideModal(confirmModal);
                }
                if (event.target === successModal) {
                    hideModal(successModal);
                }
            });
        });
    </script>
@endpush