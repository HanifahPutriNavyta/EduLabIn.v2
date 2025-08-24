@extends('layouts.app')

@section('title', 'Absensi Praktikan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/asprak.css') }}">
<style>
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
    z-index: 9999;
    pointer-events: auto;
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
<main class="container mt-4 mb-5">
  <h1 class="judul-halaman">Absensi Praktikan</h1>

  <!-- Search Bar -->
  <div class="search-container mb-3 position-relative">
    <input type="text" class="form-control search-input" placeholder="Cari judul, mata kuliah, kelas, atau deskripsi..." aria-label="Search">
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

    @foreach($pertemuans as $pertemuan)
    <div class="pertemuan-card" data-matkul="{{ $pertemuan->kelasPraktikum->mataKuliah->mk_id ?? '' }}" data-absensi-id="{{ $pertemuan->absensi_id }}">
      <div class="card-header">
        {{ $pertemuan->judul }}
        <button type="button" class="btn-close btn-delete-card text-white" aria-label="Close"></button>
      </div>
      <div class="card-body">
        <div class="card-detail">
          
            <p>Tanggal : {{ $pertemuan->tanggal->translatedFormat('d F Y') }}</p>
          <p>Deskripsi : {{ $pertemuan->deskripsi }}</p>
          <p>File :
            @if($pertemuan->upload_file)
            <button class="btn btn-outline-dark btn-sm ms-2"
              data-bs-toggle="modal"
              data-bs-target="#pdfModal"
              data-judul="{{ $pertemuan->judul }} - Absensi"
              data-file="{{ asset('storage/' . $pertemuan->upload_file) }}"
              data-download="{{ asset('storage/' . $pertemuan->upload_file) }}">
              <i class="bi bi-file-earmark-pdf"></i> File
            </button>
            @else
            <span class="text-muted">Tidak ada file</span>
            @endif
          </p>
        </div>
        <a href="{{ route('absensi-praktikan.edit', $pertemuan->absensi_id) }}" class="btn-edit">
          Edit
        </a>
      </div>
    </div>
    @endforeach
  </div>

  <button class="fab-button" id="createAbsensiBtn">
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
          <button type="button" class="btn btn-secondary" id="cancelBtn">Tidak</button>
          <button type="button" class="btn btn-danger" id="confirmBtn">Iya</button>
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
        <p>Absensi Telah Berhasil Di Hapus!</p>
        <div class="modal-buttons">
          <button type="button" class="btn btn-success" id="okBtn">OK</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal PDF -->
<div class="modal fade justify-content-center" id="pdfModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalJudulText">File Absensi</h5>
        <button type="button" class="btn-close custom-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
       <style>
          .custom-close {
            filter: none !important;
            color: #000 !important;
            opacity: 1 !important;
          }
        </style>
      <div class="modal-body text-center py-4">
        <iframe id="pdfPreviewFrame" src="" width="100%" style="border: none; display: none; min-height: 60vh; max-height: 80vh;"></iframe>
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
  let cardToDelete = null;

  // Initialize Bootstrap modals
  const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
  const successModal = new bootstrap.Modal(document.getElementById('successModal'));
  const pdfModal = document.getElementById('pdfModal');

  // Route Create Absensi
  document.getElementById('createAbsensiBtn').addEventListener('click', function() {
    window.location.href = '{{ route("absensi-praktikan.create", $kelas_id) }}';
  });

  // PDF Modal Event Listeners
  pdfModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const judul = button.getAttribute('data-judul');
    const fileUrl = button.getAttribute('data-file');
    const downloadUrl = button.getAttribute('data-download');

    // Set modal title and text
    const modalJudulText = document.getElementById('modalJudulText');
    const modalPertemuanText = document.getElementById('modalPertemuanText');
    const downloadBtn = document.getElementById('downloadPdfBtn');
    const pdfPreviewFrame = document.getElementById('pdfPreviewFrame');
    const noPreview = document.getElementById('noPreview');

    modalJudulText.textContent = judul ? `File Absensi: ${judul}` : 'File Absensi';
    modalPertemuanText.textContent = judul ? `File Absensi: ${judul}` : '';

    // Set download link
    downloadBtn.href = downloadUrl || '#';

    // Set responsive iframe height
    function setIframeHeight() {
      const windowHeight = window.innerHeight;
      const headerHeight = 60; // approximate header height
      const footerHeight = 60; // approximate footer height
      const padding = 80; // padding for modal
      const maxHeight = windowHeight - headerHeight - footerHeight - padding;
      
      // Minimum height for small screens
      const minHeight = Math.max(300, maxHeight * 0.6);
      const finalHeight = Math.min(maxHeight, Math.max(minHeight, 500));
      
      pdfPreviewFrame.style.height = finalHeight + 'px';
    }

    // Only preview if file is PDF
    if (fileUrl && fileUrl.toLowerCase().endsWith('.pdf')) {
      setIframeHeight();
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

  // Clear iframe src on modal close to release memory
  pdfModal.addEventListener('hidden.bs.modal', function() {
    const pdfPreviewFrame = document.getElementById('pdfPreviewFrame');
    if (pdfPreviewFrame) {
      pdfPreviewFrame.src = '';
    }
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => backdrop.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
  });

  // Handle window resize for responsive iframe
  window.addEventListener('resize', function() {
    const pdfPreviewFrame = document.getElementById('pdfPreviewFrame');
    if (pdfPreviewFrame && pdfPreviewFrame.style.display !== 'none') {
      const windowHeight = window.innerHeight;
      const headerHeight = 60;
      const footerHeight = 60;
      const padding = 80;
      const maxHeight = windowHeight - headerHeight - footerHeight - padding;
      const minHeight = Math.max(300, maxHeight * 0.6);
      const finalHeight = Math.min(maxHeight, Math.max(minHeight, 500));
      
      pdfPreviewFrame.style.height = finalHeight + 'px';
    }
  });

  // Delete functionality
  function attachDeleteListeners() {
    const closeButtons = document.querySelectorAll('.btn-delete-card');
    closeButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Get the card element and pertemuan info
        cardToDelete = this.closest('.pertemuan-card');
        const cardHeader = cardToDelete.querySelector('.card-header');
        let pertemuanTitle = cardHeader.textContent.trim();

        // Update modal text with specific pertemuan info
        document.getElementById('pertemuanToDelete').textContent = `"${pertemuanTitle}"`;

        // Show confirmation modal
        confirmModal.show();
      });
    });
  }

  // Initialize delete listeners
  attachDeleteListeners();

  // Event listener untuk tombol "Iya" (Confirm Delete) - AJAX version
  document.getElementById('confirmBtn').addEventListener('click', function() {
    if (cardToDelete) {
      const absensiId = cardToDelete.getAttribute('data-absensi-id');
      confirmModal.hide();
      // AJAX DELETE request
      fetch(`{{ url('absensi-praktikan') }}/${absensiId}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ _method: 'DELETE' })
      })
      .then(response => {
        if (response.ok) {
          // Remove card from DOM
          cardToDelete.remove();
          // Show success modal
          successModal.show();
        } else {
          return response.json().then(data => { throw new Error(data.message || 'Gagal menghapus absensi'); });
        }
      })
      .catch(error => {
        alert('Gagal menghapus absensi: ' + error.message);
      });
    } else {
      console.error('No card to delete');
    }
  });

  // Tombol "Tidak" pada modal konfirmasi
  document.getElementById('cancelBtn').addEventListener('click', function() {
    confirmModal.hide();
  });

  // Set redirect URL for OK button after delete
  let redirectUrl = "{{ $pertemuans->isNotEmpty() ? route('absensi-praktikan.index', $pertemuans->first()->kelas_id) : route('dashboard') }}";
  document.getElementById('okBtn').addEventListener('click', function() {
    successModal.hide();
    window.location.href = redirectUrl;
  });

  // Search functionality
  const searchInput = document.querySelector('.search-input');
  const pertemuanCards = document.querySelectorAll('.pertemuan-card');
  const noDataMessage = document.getElementById('noDataMessage');

  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      let visibleCards = 0;

      pertemuanCards.forEach(card => {
        // Get searchable content from each card
        const judul = card.querySelector('.card-header')?.textContent.toLowerCase() || '';
        const kelas = card.querySelector('.card-detail p:nth-child(1)')?.textContent.toLowerCase() || '';
        const mataKuliah = card.querySelector('.card-detail p:nth-child(2)')?.textContent.toLowerCase() || '';
        const tanggal = card.querySelector('.card-detail p:nth-child(3)')?.textContent.toLowerCase() || '';
        const deskripsi = card.querySelector('.card-detail p:nth-child(4)')?.textContent.toLowerCase() || '';

        // Combine all searchable text
        const combinedText = `${judul} ${kelas} ${mataKuliah} ${tanggal} ${deskripsi}`;
        
        // Show/hide card based on search match
        if (combinedText.includes(searchTerm)) {
          card.style.display = '';
          visibleCards++;
        } else {
          card.style.display = 'none';
        }
      });

      // Show/hide "no data found" message
      if (noDataMessage) {
        if (visibleCards === 0 && searchTerm.trim() !== '') {
          noDataMessage.style.display = 'block';
        } else {
          noDataMessage.style.display = 'none';
        }
      }
    });
  }
});
</script>
@endpush