@extends('layouts.app')

@section('title', 'File Nilai - ' . $matkul->matakuliah->nama_mk . ' - ' . $matkul->kode_kelas)
@push('styles')
<link rel="stylesheet" href="{{ asset('css/dosen.css') }}">
@endpush
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="judul-halaman">File Nilai - {{ $matkul->matakuliah->nama_mk }} - {{ $matkul->kode_kelas }}</h1>
    
</div>

<!-- Filter/Search -->
<div class="search-container mb-3 position-relative">
    <input type="text" class="form-control search-input" placeholder="Cari judul atau tanggal..." aria-label="Search">
    <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
</div>
<div class="line-div"></div>
<div id="noResultsMessage" class="text-center" style="display: none;">
    <p class="text-muted">Tidak ada file nilai yang ditemukan.</p>
</div>

<!-- Daftar Nilai -->
<div class="pertemuan-list">
    @forelse($nilai as $n)
    <div class="pertemuan-card">
        <div class="card-header">
            {{ $n->judul }}
        </div>
        <div class="card-body">
            <div class="card-detail">
                <p>Tanggal : {{ $n->tanggal->format('d F Y') }}</p>
                <p>Diupload oleh : {{ $n->asprak->user->profil->nama_lengkap }}</p>
                <p>File :
                    @if($n->upload_file)
                    <button class="btn btn-outline-dark btn-sm ms-2"
                        data-bs-toggle="modal"
                        data-bs-target="#pdfModal"
                        data-judul="{{ $n->judul }}"
                        data-file="{{ asset('storage/nilai/' . $n->upload_file) }}"
                        data-download="{{ route('informasi-nilai.download', $n) }}">
                        <i class="bi bi-file-earmark-pdf"></i> 
                    </button>
                    @else
                    <span class="text-muted ms-2">Tidak ada file</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info">
        Belum ada file nilai yang diupload untuk kelas ini.
    </div>
    @endforelse
</div>
<!-- Modal PDF -->
<div class="modal fade justify-content-center" id="pdfModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalJudulText">File Nilai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <iframe id="pdfPreviewFrame" src="" width="100%" height="500px" style="border: none; display: none;"></iframe>
                <div id="noPreview" style="display: none;">
                    <i class="bi bi-file-earmark-pdf pdf-icon-large"></i>
                    <p class="mt-3" id="modalPertemuanText"></p>
                    <p class="text-muted">Preview hanya tersedia untuk file PDF.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <a href="#" class="btn btn-warning" id="downloadPdfBtn" target="_blank" rel="noopener">
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
        const pdfModal = document.getElementById('pdfModal');
        const pdfPreviewFrame = document.getElementById('pdfPreviewFrame');
        const downloadBtn = document.getElementById('downloadPdfBtn');
        const modalJudulText = document.getElementById('modalJudulText');
        const modalPertemuanText = document.getElementById('modalPertemuanText');
        const noPreview = document.getElementById('noPreview');

        pdfModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const judul = button.getAttribute('data-judul');
            const fileUrl = button.getAttribute('data-file');
            const downloadUrl = button.getAttribute('data-download');

            // Set modal title and text
            modalJudulText.textContent = judul ? `File Nilai: ${judul}` : 'File Nilai';
            modalPertemuanText.textContent = judul ? `File Nilai: ${judul}` : '';

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
                // Show a message for non-PDF files
                noPreview.style.display = '';
            } else {
                pdfPreviewFrame.src = '';
                pdfPreviewFrame.style.display = 'none';
                noPreview.style.display = '';
            }
        });

        // Clear iframe src on modal close to release memory
        pdfModal.addEventListener('hidden.bs.modal', function() {
            pdfPreviewFrame.src = '';
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search logic
    const searchInput = document.querySelector('.search-input');
    const pertemuanCards = document.querySelectorAll('.pertemuan-card');
    const noResults = document.getElementById('noResultsMessage');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let hasVisible = false;

            pertemuanCards.forEach(card => {
                const judul = card.querySelector('.card-header')?.textContent.toLowerCase() || '';
                const tanggal = card.querySelector('.card-detail p')?.textContent.toLowerCase() || '';
                const combined = `${judul} ${tanggal}`;
                const shouldShow = combined.includes(searchTerm);

                card.style.display = shouldShow ? '' : 'none';
                if (shouldShow) hasVisible = true;
            });

            if (noResults) {
                noResults.style.display = hasVisible ? 'none' : 'block';
            }
        });
    }
});
</script>
@endpush