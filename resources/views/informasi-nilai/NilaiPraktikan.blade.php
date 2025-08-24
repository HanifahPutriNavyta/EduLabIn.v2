@extends('layouts.app')

@section('title', 'Nilai Praktikan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/asprak.css') }}">
@endpush

@section('content')
<m window.location.href='{{ route("informasi-nilai-asprak.create", $kelas_id) }}' ; in class="container mt-4 mb-5">
  <h1 class="judul-halaman">Penilaian</h1>

  <!-- Search Bar -->
  <div class="search-container mb-3 position-relative">
    <input type="text" class="form-control search-input" placeholder="Cari judul, mata kuliah, atau deskripsi..." aria-label="Search">
    <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
  </div>

  <div class="line-div"></div>

  <!-- Daftar Pertemuan -->
  <div class="pertemuan-list">
    <!-- Pesan tidak ada data -->
    <div id="noDataMessage" class="text-center py-5" style="display: none;">
      <i class="bi bi-search" style="font-size: 3rem; color: #6c757d;"></i>
      <h5 class="mt-3 text-muted">Tidak ada data ditemukan</h5>
      <p class="text-muted">Coba ubah kata kunci pencarian Anda</p>
    </div>

    @foreach($nilaiPraktikans as $nilaiPraktikan)
    @php
    $matkul = collect($matkuls)->firstWhere('kode', $nilaiPraktikan['matkul_kode']);
    @endphp
    <div class="pertemuan-card" data-matkul="{{ $nilaiPraktikan['matkul_kode'] }}" data-nilai-id="{{ $nilaiPraktikan['nilai_id'] }}">
      <div class="card-header">
        {{ $nilaiPraktikan['judul'] }}
        <button type="button" class="btn-close btn-delete-card text-white" aria-label="Close" data-nilai-id="{{ $nilaiPraktikan['nilai_id'] }}"></button>
      </div>
      <div class="card-body">
        <div class="card-detail">
          <p>Mata Kuliah : {{ $matkul['nama_mk'] ?? 'N/A' }}</p>
          <p>Tanggal : {{ $nilaiPraktikan['tanggal'] }}</p>
          <p>Deskripsi : {{ $nilaiPraktikan['deskripsi'] }}</p>
          <p>File :
            @if($nilaiPraktikan['file_nilai_praktikan'])
            <button class="btn btn-outline-dark btn-sm ms-2"
              data-bs-toggle="modal"
              data-bs-target="#pdfModal"
              data-judul="{{ $nilaiPraktikan['judul'] }} - Nilai"
              data-file="{{ asset('storage/nilai/' . $nilaiPraktikan['file_nilai_praktikan']) }}"
              data-download="{{ asset('storage/nilai/' . $nilaiPraktikan['file_nilai_praktikan']) }}">
              <i class="bi bi-file-earmark-pdf"></i> File
            </button>
            @else
            <span class="text-muted">Tidak ada file</span>
            @endif
          </p>
        </div>
        <a href="{{ route('informasi-nilai-asprak.edit', [$kelas_id, $nilaiPraktikan['nilai_id']]) }}" class="btn-edit">
          Edit
        </a>
      </div>

      <!-- Hidden form for delete -->
      <form id="delete-form-{{ $nilaiPraktikan['nilai_id'] }}" action="{{ route('informasi-nilai-asprak.destroy', [$kelas_id, $nilaiPraktikan['nilai_id']]) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
      </form>
    </div>
    @endforeach
  </div>

  <button class="fab-button" id="createNilaiPraktikanBtn">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <line x1="12" y1="5" x2="12" y2="19"></line>
      <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
  </button>

  </main>

  <!-- Modal Konfirmasi Hapus -->
  <div id="confirmModal" class="modal fade" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <p>Apakah Anda Yakin Ingin Menghapus? <span id="pertemuanToDelete"></span>?</p>
          <div class="modal-buttons">
            <button id="cancelBtn" class="btn btn-secondary">Tidak</button>
            <button id="confirmBtn" class="btn btn-danger">Iya</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Notifikasi Berhasil -->
  <div id="successModal" class="modal fade" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <p>Penilaian Telah Berhasil Di Hapus!</p>
          <div class="modal-buttons">
            <button id="okBtn" class="btn btn-primary">OK</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal PDF -->
  <div class="modal fade justify-content-center" id="pdfModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="pdfModalLabel">File Penilaian</h5>
          <button type="button" class="btn-close custom-close" aria-label="Close" data-bs-dismiss="modal"></button>
        </div>
        <style>
          .custom-close {
            filter: none !important;
            color: #000 !important;
            opacity: 1 !important;
          }
        </style>

        <div class="modal-body text-center py-4">
          <iframe id="pdfPreviewFrame" src="" width="100%" height="500px" style="border: none; display: none;"></iframe>
          <div id="noPreview" style="display: none;">
            <i class="bi bi-file-earmark-pdf pdf-icon-large"></i>
            <p class="mt-3" id="modalPertemuanText"></p>
            <p class="text-muted">Preview hanya tersedia untuk file PDF.</p>
          </div>
        </div>
        <div class="modal-footer justify-content-end">
          <a href="#" class="btn btn-warning" id="downloadPdfBtn" target="_blank" rel="noopener" download>
            <i class="bi bi-download"></i> Download
          </a>
        </div>
      </div>
    </div>
  </div>
  @endsection

  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Bootstrap modals
      const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
      const successModal = new bootstrap.Modal(document.getElementById('successModal'));
      const pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'));

      // Search functionality
      const searchInput = document.querySelector('.search-input');
      const pertemuanCards = document.querySelectorAll('.pertemuan-card');
      const noDataMessage = document.getElementById('noDataMessage');

      searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        let visibleCards = 0;

        pertemuanCards.forEach(card => {
          const cardText = card.textContent.toLowerCase();
          if (cardText.includes(searchTerm)) {
            card.style.display = 'block';
            visibleCards++;
          } else {
            card.style.display = 'none';
          }
        });

        // Show/hide no data message
        if (visibleCards === 0 && searchTerm !== '') {
          noDataMessage.style.display = 'block';
        } else {
          noDataMessage.style.display = 'none';
        }
      });

      // Route Create Nilai Praktikan
      document.getElementById('createNilaiPraktikanBtn').addEventListener('click', function() {
        window.location.href = '{{ route("informasi-nilai-asprak.create", $kelas_id) }}';
      });

      // PDF Modal Event Listeners
      document.getElementById('pdfModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const judul = button.getAttribute('data-judul');
        const fileUrl = button.getAttribute('data-file');
        const downloadUrl = button.getAttribute('data-download');

        document.getElementById('pdfModalLabel').textContent = judul || 'File Penilaian';
        document.getElementById('modalPertemuanText').textContent = judul || 'File Penilaian';
        document.getElementById('downloadPdfBtn').href = downloadUrl || '#';

        const pdfPreviewFrame = document.getElementById('pdfPreviewFrame');
        const noPreview = document.getElementById('noPreview');

        if (fileUrl && fileUrl.toLowerCase().endsWith('.pdf')) {
          pdfPreviewFrame.src = fileUrl;
          pdfPreviewFrame.style.display = '';
          noPreview.style.display = 'none';
        } else if (fileUrl) {
          pdfPreviewFrame.src = '';
          pdfPreviewFrame.style.display = 'none';
          noPreview.style.display = '';
        } else {
          pdfPreviewFrame.src = '';
          pdfPreviewFrame.style.display = 'none';
          noPreview.style.display = '';
        }
      });

      document.getElementById('pdfModal').addEventListener('hidden.bs.modal', function() {
        const pdfPreviewFrame = document.getElementById('pdfPreviewFrame');
        if (pdfPreviewFrame) {
          pdfPreviewFrame.src = '';
        }
      });

      // Delete functionality
      let nilaiIdToDelete = null;

      function attachDeleteListeners() {
        const closeButtons = document.querySelectorAll('.btn-delete-card');
        closeButtons.forEach(button => {
          button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Get the nilai ID and card element
            nilaiIdToDelete = this.getAttribute('data-nilai-id');
            const cardToDelete = this.closest('.pertemuan-card');
            const cardHeader = cardToDelete.querySelector('.card-header');
            let pertemuanTitle = cardHeader.textContent.trim().replace('×', '').trim();

            // Update modal text with specific pertemuan info
            document.getElementById('pertemuanToDelete').textContent = `"${pertemuanTitle}"`;

            // Show confirmation modal
            confirmModal.show();
          });
        });
      }

      // Initialize delete listeners
      attachDeleteListeners();

      // Event listener untuk tombol "Iya" (Confirm Delete)
      document.getElementById('confirmBtn').addEventListener('click', function() {
        if (nilaiIdToDelete) {
          // Hide confirmation modal first
          confirmModal.hide();

          // Submit the delete form
          const deleteForm = document.getElementById('delete-form-' + nilaiIdToDelete);
          if (deleteForm) {
            deleteForm.submit();
          }
        }
      });

      // Tombol "Tidak" pada modal konfirmasi
      document.getElementById('cancelBtn').addEventListener('click', function() {
        confirmModal.hide();
      });

      // Tombol "OK" pada modal sukses
      document.getElementById('okBtn').addEventListener('click', function() {
        successModal.hide();
        window.location.href = "{{ route('informasi-nilai-asprak.index', $kelas_id) }}";
      });

      // Cleanup when modals are hidden
      ['confirmModal', 'successModal', 'pdfModal'].forEach(modalId => {
        document.getElementById(modalId).addEventListener('hidden.bs.modal', function() {
          const backdrops = document.querySelectorAll('.modal-backdrop');
          backdrops.forEach(backdrop => backdrop.remove());
          document.body.classList.remove('modal-open');
          document.body.style.overflow = '';
          document.body.style.paddingRight = '';
        });
      });
    });
  </script>
  @endpush