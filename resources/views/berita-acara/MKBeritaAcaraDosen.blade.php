@extends('layouts.app')

@section('title', 'Berita Acara')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/dosen.css') }}">
@endpush
@section('content')

<h1 class="judul-halaman">Berita Acara</h1>

<div class="search-container mb-3 position-relative">
    <input type="text" class="form-control search-input" placeholder="Cari Mata Kuliah" aria-label="Search">
    <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
</div>

<div class="line-div"></div>

<!-- No Results Message --> 
<div id="noResultsMessage" class="text-center" style="display: none;">
    <p class="text-muted">Tidak ada mata kuliah yang ditemukan.</p>
</div>

<!-- List Kelas -->
<div class="matkul-list">
    @forelse($kelas as $k)
    <a href="{{ route('berita-acara.showKelas', $k->kelas_id) }}" class="matkul-item-link">
        <div class="matkul-item">
            {{ $k->matakuliah->nama_mk }} - {{ $k->kode_kelas }}
        </div>
    </a>
    @empty
    <div class="alert alert-info">
        Belum ada kelas yang tersedia.
    </div>
    @endforelse
</div>

<!-- Modal PDF -->
<div class="modal fade justify-content-center" id="pdfModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalJudulText">File Berita Acara</h5>
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
            const judul = button.getAttribute('data-pertemuan');
            const fileUrl = button.getAttribute('data-file');
            const downloadUrl = button.getAttribute('data-download');

            // Set modal title and text
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
            pdfPreviewFrame.src = '';
        });
    });


    function setupFilter(dropdownId, cardClass, titleClass) {
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) {
            console.warn(`Dropdown dengan ID ${dropdownId} tidak ditemukan`);
            return;
        }

        // Debounce untuk performa
        let filterTimeout;

        dropdown.addEventListener('change', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(() => {
                const searchTerm = this.value.toLowerCase();
                const cards = document.querySelectorAll(`.${cardClass}`);

                cards.forEach(card => {
                    const titleElement = card.querySelector(`.${titleClass}`);
                    if (!titleElement) {
                        console.warn(`Element dengan class ${titleClass} tidak ditemukan dalam card`);
                        return;
                    }

                    const cardTitle = titleElement.textContent.toLowerCase();
                    const shouldShow = searchTerm === 'all' ||
                        searchTerm === '' ||
                        cardTitle.includes(searchTerm);

                    card.style.display = shouldShow ? 'block' : 'none';
                    card.style.opacity = shouldShow ? '1' : '0';
                    card.style.height = shouldShow ? 'auto' : '0';
                    card.style.margin = shouldShow ? '' : '0';
                    card.style.padding = shouldShow ? '' : '0';
                    card.style.border = shouldShow ? '' : 'none';
                });
            }, 300);
        });
    }

    /**
     * Inisialisasi semua filter saat dokumen siap
     */
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.search-input');
        const matkulLinks = document.querySelectorAll('.matkul-item-link');
        const noResults = document.getElementById('noResultsMessage');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasVisible = false;

                matkulLinks.forEach(link => {
                    const text = link.textContent.toLowerCase();
                    const shouldShow = text.includes(searchTerm);
                    link.style.display = shouldShow ? '' : 'none';
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