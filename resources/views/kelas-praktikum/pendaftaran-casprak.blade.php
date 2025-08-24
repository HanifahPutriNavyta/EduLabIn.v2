@extends('layouts.app')
@section('title', 'Pendaftaran Kelas Praktikum')
@push('styles')
<link href="{{ asset('css/casprak.css') }}" rel="stylesheet">
@endpush
@section('content')
<h1 class="judul-halaman">Pendaftaran</h1>

<!-- Search Bar -->
<div class="search-container mb-3 position-relative">
    <input type="text" class="form-control search-input" placeholder="Cari Mata Kuliah" aria-label="Search">
    <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
</div>



<div class="line-div"></div>

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
                @if(!$matkul['has_pendaftaran'])
                    <span class="text-muted">Pendaftaran belum dibuka</span>
                @elseif(!$matkul['has_classes'])
                    <span class="text-danger">Belum ada kelas tersedia</span>
                @elseif($matkul['is_full'])
                    <span class="text-warning">Kuota penuh</span>
                @elseif(!$matkul['available'])
                    <span class="text-secondary">Tidak tersedia</span>
                @else
                    <a href="{{ route('calonAsprak.FormPendaftaranCasprak', $matkul['id']) }}" class="btn-daftar">
                        Daftar
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
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

                const noResults = document.getElementById('noResultsMessage');
                if (noResults) {
                    noResults.style.display = hasVisible ? 'none' : 'block';
                }
            });
        }
    });
</script>
@endpush