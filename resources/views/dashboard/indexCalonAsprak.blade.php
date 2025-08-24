@extends('layouts.app')
@section('title', 'Dashboard Calon Asprak')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/casprak.css') }}">
@endpush
@section('content')
<div class="dashboard-header text-center mb-4">
    <img src="{{ asset('img/imgPengumumanFilkom.png') }}" alt="header" class="header-image">
</div>

<!-- Pendaftaran Section -->
<div class="section">
    <div class="section-header">
        <a href="{{ route('calonAsprak.PendaftaranCasprak') }}" class="section-title">
            Pendaftaran
            <img src="{{ asset('img/IconRightOutline.png') }}" alt=">" class="section-arrow">
        </a>
    </div>

    <div class="line-div"></div>

    <!-- Search Bar -->
    <div class="search-container mb-3 position-relative">
        <input type="text" class="form-control search-input" placeholder="Cari Mata Kuliah" aria-label="Search">
        <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
    </div>

    <!-- No Results Message -->
    <div id="noResultsMessage" class="text-center" style="display: none;">
        <p class="text-muted">Tidak ada mata kuliah yang ditemukan.</p>
    </div>

    <!-- Mata Kuliah Cards -->
    <div class="row g-3">
        @foreach($matkulData as $matkul)
        <div class="col-md-6">
            <div class="matkul-card">
                <h3 class="matkul-name">{{ $matkul['nama'] }}</h3>
                <div class="matkul-requirements">
                    <p class="req-title">Ketentuan:</p>
                    <ul>
                        @foreach($matkul['ketentuan'] as $k)
                        <li>{{ $k }}</li>
                        @endforeach

                    </ul>
                </div>
                <div class="matkul-footer">
                    <span class="kuota">Jumlah Kelas Praktikum: {{ $matkul['kuota'] }}</span>
                    <a href="{{ route('calonAsprak.FormPendaftaranCasprak', $matkul['id']) }}" class="btn-daftar">
                        Daftar
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Pengumuman Section -->
<div class="section">
    <div class="section-header">
        <a href="{{ route('calonAsprak.PengumumanCasprak') }}" class="section-title">
            Pengumuman
            <img src="{{ asset('img/IconRightOutline.png') }}" alt="" class="section-arrow">
        </a>
    </div>
    <div class="line-div"></div>

    <div class="pengumuman-list">
        @foreach($pengumumanData as $pengumuman)
        <a href="{{ route('calonAsprak.DetailPengumumanCasprak', $pengumuman->pengumuman_id) }}" class="pengumuman-item">
            <div class="pengumuman-card">
                <div class="pengumuman-content">
                    <h3 class="pengumuman-title">{{ $pengumuman['judul'] }}</h3>
                    <p class="pengumuman-konten">{{ Str::limit(strip_tags($pengumuman['deskripsi']), 100) }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
        const matkulCards = document.querySelectorAll('.matkul-card');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasVisible = false;

                matkulCards.forEach(card => {
                    const title = card.querySelector('.matkul-name').textContent.toLowerCase();
                    const shouldShow = title.includes(searchTerm);
                    card.style.display = shouldShow ? '' : 'none';
                    if (shouldShow) hasVisible = true;
                });

                // (Opsional) Tampilkan pesan jika tidak ada hasil
                const noResults = document.getElementById('noResultsMessage');
                if (noResults) {
                    noResults.style.display = hasVisible ? 'none' : 'block';
                }
            });
        }
    });
</script>
@endpush