@extends('layouts.app')

@section('title', 'Berita Acara Asprak')

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
        <h1 class="judul-halaman">Berita Acara</h1>

        <!-- Search Bar -->
        <div class="search-container mb-3 position-relative">
            <input type="text" class="form-control search-input" placeholder="Cari Mata Kuliah atau Judul" aria-label="Search">
            <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
        </div>

        <div class="line-div"></div>

        <!-- Pesan tidak ada data -->
        <div id="noDataMessage" class="text-center py-5" style="display: none;">
            <i class="bi bi-search" style="font-size: 3rem; color: #6c757d;"></i>
            <h5 class="mt-3 text-muted">Tidak ada data ditemukan</h5>
            <p class="text-muted">Coba ubah kata kunci pencarian Anda</p>
        </div>

        <!-- Daftar Pertemuan -->
        <div class="pertemuan-list">
            @forelse($beritaAcaras ?? [] as $beritaAcara)
            <div class="pertemuan-card" data-matkul="{{ $beritaAcara->kelasPraktikum->mataKuliah->mk_id ?? '' }}" data-berita-id="{{ $beritaAcara->berita_id }}">
                <div class="card-header">
                    {{ $beritaAcara->judul ?? 'Tidak ada judul' }}
                    <button type="button" class="btn-close btn-delete-card text-white" aria-label="Close"></button>
                </div>
                <div class="card-body">
                    <div class="card-detail">
                        
                        <p>Tanggal : {{ $beritaAcara->tanggal_kegiatan ? \Carbon\Carbon::parse($beritaAcara->tanggal_kegiatan)->format('d F Y') : 'N/A' }}</p>
                        <p>Tipe Pertemuan : {{ $beritaAcara->tipe_pertemuan ?? 'N/A' }}</p>
                        
                        <div class="d-flex align-items-center mb-2">
                            <span style="min-width: 40px;">File : </span>
                            <div class="d-flex gap-3">
                                @if($beritaAcara->upload_berita_acara)
                                <button class="btn btn-outline-dark btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#pdfModal"
                                    data-judul="{{ $beritaAcara->judul }} - Berita Acara"
                                    data-file="{{ asset('storage/berita-acara/file/' . $beritaAcara->upload_berita_acara) }}"
                                    data-download="{{ route('berita-acara.download', $beritaAcara->berita_id) }}">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </button>
                                @endif
                                @if($beritaAcara->upload_bukti_pertemuan && $beritaAcara->tipe_pertemuan == 'daring')
                                <button class="btn btn-outline-dark btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#pdfModal"
                                    data-judul="{{ $beritaAcara->judul }} - Bukti Pertemuan"
                                    data-file="{{ asset('storage/berita-acara/foto/' . $beritaAcara->upload_bukti_pertemuan) }}"
                                    data-download="{{ route('berita-acara.download-bukti', $beritaAcara->berita_id) }}">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </button>
                                @endif
                                @if(!$beritaAcara->upload_berita_acara && (!$beritaAcara->upload_bukti_pertemuan || $beritaAcara->tipe_pertemuan != 'daring'))
                                <span class="text-muted">Tidak ada file</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('berita-acara.edit', $beritaAcara->berita_id) }}" class="btn-edit">
                        Edit
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #6c757d;"></i>
                <h5 class="mt-3 text-muted">Belum ada berita acara</h5>
                <p class="text-muted">Klik tombol + untuk membuat berita acara baru</p>
            </div>
            @endforelse
        </div>

        <button class="fab-button" id="createBeritaAcaraBtn">
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
                <p>Apakah Anda Yakin Ingin Menghapus <span id="pertemuanToDelete"></span>?</p>
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
                <p>Berita Acara Telah Berhasil Dihapus!</p>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-success" id="okBtn">OK</button>
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
                <h5 class="modal-title" id="modalJudulText">File Berita Acara</h5>
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
    let cardToDelete = null;

    // Initialize Bootstrap modals
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    const pdfModal = document.getElementById('pdfModal');

    // Route Create Berita Acara
    document.getElementById('createBeritaAcaraBtn').addEventListener('click', function() {
        window.location.href = '{{ route("berita-acara.create", $kelas_id) }}';
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

        modalJudulText.textContent = judul ? `File Berita Acara: ${judul}` : 'File Berita Acara';
        modalPertemuanText.textContent = judul ? `File Berita Acara: ${judul}` : '';

        // Set download link
        downloadBtn.href = downloadUrl || '#';

        // Only preview if file is PDF
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

    // Clear iframe src on modal close to release memory
    pdfModal.addEventListener('hidden.bs.modal', function() {
        const pdfPreviewFrame = document.getElementById('pdfPreviewFrame');
        if (pdfPreviewFrame) {
            pdfPreviewFrame.src = '';
        }
    });

    // Delete functionality
    function attachDeleteListeners() {
        const closeButtons = document.querySelectorAll('.btn-delete-card');
        console.log('Found delete buttons:', closeButtons.length); // Debug log
        closeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                console.log('Delete button clicked'); // Debug log
                e.preventDefault();
                e.stopPropagation();

                // Get the card element and berita acara info
                cardToDelete = this.closest('.pertemuan-card');
                const cardHeader = cardToDelete.querySelector('.card-header');
                let judulBerita = cardHeader.textContent.trim();

                console.log('Card to delete:', cardToDelete); // Debug log
                console.log('Judul berita:', judulBerita); // Debug log

                // Update modal text with specific berita acara info
                document.getElementById('pertemuanToDelete').textContent = `"${judulBerita}"`;

                // Show confirmation modal
                confirmModal.show();
            });
        });
    }

    // Initialize delete listeners
    attachDeleteListeners();

    // Event listener untuk tombol "Iya" (Confirm Delete)
    document.getElementById('confirmBtn').addEventListener('click', function() {
        
        if (cardToDelete) {
            // Get berita acara ID from card data attribute
            const beritaId = cardToDelete.getAttribute('data-berita-id');
        
            
            // Create and submit delete form
            const form = document.createElement('form');
            form.method = 'POST';  // Perbaikan: Gunakan POST, bukan DELETE
            form.action = '{{ url("berita-acara") }}/' + beritaId;
            
            console.log('Form action:', form.action); // Debug log
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add DELETE method
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            console.log('Form method:', form.method); // Debug log
            console.log('Submitting form...'); // Debug log
            
            // Hide modal and submit form
            confirmModal.hide();
            document.body.appendChild(form);
            form.submit();
        } else {
            console.error('No card to delete');
        }
    });

    // Tombol "Tidak" pada modal konfirmasi
    document.getElementById('cancelBtn').addEventListener('click', function() {
        confirmModal.hide();
    });

    // Tombol "OK" pada modal sukses
    document.getElementById('okBtn').addEventListener('click', function() {
        successModal.hide();
        window.location.href = "{{ route('berita-acara.indexAsprak', $kelas_id) }}";
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
                const tipePertemuan = card.querySelector('.card-detail p:nth-child(4)')?.textContent.toLowerCase() || '';

                // Combine all searchable text
                const combinedText = `${judul} ${kelas} ${mataKuliah} ${tanggal} ${tipePertemuan}`;
                
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