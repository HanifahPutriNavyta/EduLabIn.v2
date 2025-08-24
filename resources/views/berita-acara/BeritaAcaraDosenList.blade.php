@extends('layouts.app')

@section('title', 'Berita Acara - ' . $kelas->matakuliah->nama_mk . ' - ' . $kelas->kode_kelas)
@push('styles')
<link rel="stylesheet" href="{{ asset('css/dosen.css') }}">

@endpush
@section('content')
<div class="d-flex justify-content-between align-items-center ">
    <h1 class="judul-halaman">Berita Acara - {{ $kelas->matakuliah->nama_mk }} - {{ $kelas->kode_kelas }}</h1>
</div>

<!-- Tambahkan sebelum .pertemuan-list -->
<div class="search-container mb-3 position-relative">
    <input type="text" class="form-control search-input" placeholder="Cari judul, tipe pertemuan, atau tanggal..." aria-label="Search">
    <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
</div>
<div class="line-div"></div>
<div id="noResultsMessage" class="text-center" style="display: none;">
    <p class="text-muted">Tidak ada berita acara yang ditemukan.</p>
</div>


<div class="pertemuan-list">
    @forelse($pertemuans as $pertemuan)
    <div class="pertemuan-card">
        <div class="card-header">
            {{ $pertemuan->judul }}
        </div>
        <div class="card-body">
            <div class="card-detail">
                <p>Tanggal : {{ $pertemuan->tanggal_kegiatan ? \Carbon\Carbon::parse($pertemuan->tanggal_kegiatan)->format('d F Y') : '-' }}</p>
                <p>Tipe Pertemuan : {{ $pertemuan->tipe_pertemuan ?? '-' }}</p>
                
                <div class="d-flex align-items-center mb-2">
                    <span style="min-width: 40px;">File : </span>
                    <div class="d-flex gap-3">
                        @if($pertemuan->upload_berita_acara)
                        <button class="btn btn-outline-dark btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#pdfModal"
                            data-judul="{{ $pertemuan->judul }} - Berita Acara"
                            data-file="{{ asset('storage/berita-acara/file/' . $pertemuan->upload_berita_acara) }}"
                            data-download="{{ route('berita-acara.download', $pertemuan->berita_id) }}">
                            <i class="bi bi-file-earmark-pdf" ></i>
                        </button>
                        @endif
                        @if($pertemuan->upload_bukti_pertemuan && $pertemuan->tipe_pertemuan == 'daring')
                        <button class="btn btn-outline-dark btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#pdfModal"
                            data-judul="{{ $pertemuan->judul }} - Bukti Pertemuan"
                            data-file="{{ asset('storage/berita-acara/foto/' . $pertemuan->upload_bukti_pertemuan) }}"
                            data-download="{{ route('berita-acara.download-bukti', $pertemuan->berita_id) }}">
                            <i class="bi bi-file-earmark-pdf" ></i>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Bukti Pertemuan File (only for Daring) -->
                
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info">
        Belum ada berita acara untuk kelas ini.
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

        // Search/filter logic
        const searchInput = document.querySelector('.search-input');
        const pertemuanCards = document.querySelectorAll('.pertemuan-card');
        const noResults = document.getElementById('noResultsMessage');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasVisible = false;

                pertemuanCards.forEach(card => {
                    // Ambil semua field yang ingin dicari
                    const judul = card.querySelector('.card-header')?.textContent.toLowerCase() || '';
                    const tipe = card.querySelector('.card-detail p:nth-child(2)')?.textContent.toLowerCase() || '';
                    const tanggal = card.querySelector('.card-detail p:nth-child(1)')?.textContent.toLowerCase() || '';

                    const combined = `${judul} ${tipe} ${tanggal}`;
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