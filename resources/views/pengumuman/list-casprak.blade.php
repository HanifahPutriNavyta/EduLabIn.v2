@extends('layouts.app')
@section('title', 'List Pengumuman')
@push('styles')
<link href="{{ asset('css/casprak.css') }}" rel="stylesheet">
@endpush
@section('content')
<h1 class="judul-halaman">Pengumuman</h1>

<!-- Search Bar -->
<div class="search-container mb-3 position-relative">
    <input type="text" class="form-control search-input" placeholder="Cari Pengumuman" aria-label="Search">
    <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
</div>

<div class="line-div"></div>
<!-- No Results Message -->
<div id="noResultsMessage" class="text-center" style="display: none;">
    <p class="text-muted">Tidak ada pengumuman yang ditemukan.</p>
</div>


<!-- List Pengumuman -->
<div class="pengumuman-list">
    @foreach($pengumumanData as $pengumuman)
    <a href="{{ route('calonAsprak.DetailPengumumanCasprak', $pengumuman['pengumuman_id']) }}" class="pengumuman-item">
        <div class="pengumuman-card">
            <div class="pengumuman-content">
                <h3 class="pengumuman-title">{{ $pengumuman['judul'] }}</h3>
                <p class="pengumuman-konten">{{ Str::limit(strip_tags($pengumuman['deskripsi']), 150) }}</p>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    const pengumumanItems = document.querySelectorAll('.pengumuman-item');
    const noResults = document.getElementById('noResultsMessage');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let hasVisible = false;

            pengumumanItems.forEach(item => {
                const title = item.querySelector('.pengumuman-title')?.textContent.toLowerCase() || '';
                const konten = item.querySelector('.pengumuman-konten')?.textContent.toLowerCase() || '';
                const combined = `${title} ${konten}`;
                const shouldShow = combined.includes(searchTerm);

                item.style.display = shouldShow ? '' : 'none';
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