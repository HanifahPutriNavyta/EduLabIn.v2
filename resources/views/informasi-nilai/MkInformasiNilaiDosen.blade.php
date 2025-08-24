@extends('layouts.app')

@section('title', 'Informasi Nilai')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/dosen.css') }}">
@endpush
@section('content')
<h1 class="judul-halaman">Informasi Nilai</h1>

<!-- Filter -->
<div class="search-container mb-3 position-relative">
    <input type="text" class="form-control search-input" placeholder="Cari Mata Kuliah" aria-label="Search">
    <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
</div>

<div class="line-div"></div>

<!-- No Results Message -->
<div id="noResultsMessage" class="text-center" style="display: none;">
    <p class="text-muted">Tidak ada mata kuliah yang ditemukan.</p>
</div>

<!-- Daftar Mata Kuliah -->
<div class="matkul-list">
    @forelse($kelas as $k)
    <a href="{{ route('informasi-nilai.show', $k) }}" class="matkul-item-link">
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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.search-input');
        const matkulItems = document.querySelectorAll('.matkul-item-link');

        searchInput.addEventListener('input', function() {
            const searchTerm = searchInput.value.toLowerCase();

            matkulItems.forEach(function(item) {
                const matkulName = item.querySelector('.matkul-item').textContent.toLowerCase();
                item.style.display = matkulName.includes(searchTerm) ? 'block' : 'none';
            });

            // Tampilkan pesan jika tidak ada hasil
            const noResults = document.getElementById('noResultsMessage');
            if (noResults) {
                const hasVisible = Array.from(matkulItems).some(item => item.style.display === 'block');
                noResults.style.display = hasVisible ? 'none' : 'block';
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
