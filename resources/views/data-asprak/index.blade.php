@extends('layouts.app')
@section('title', 'Data Asisten Praktikum - EduLabIn')

@section('content')
<style>

.search-container .form-control.search-input{
    max-width: 100%; 
    margin-bottom: 20px;
    padding: 10px 0px 10px 0px;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    background-color: var(--neutral-100) !important;
    color: var(--neutral-300) !important;
    border: none !important;
    border-radius: 10px !important;
    box-shadow: 0px 4px 4px rgba(117, 117, 117, 0.5) !important;
    height: 40px;
    padding: 10px 20px 10px 30px;
    font-size: 14px
}

.search-icon {
    pointer-events: none;
    z-index: 2;
    font-size: 1.2rem;
    color: var(--neutral-300);
    padding-right: 10px;
}

.form-control.search-input:focus {
    background-color: var(--neutral-100) !important;
    box-shadow: 0px 4px 4px rgba(117, 117, 117, 0.5) !important;
    color: var(--neutral-300) !important;
    border: none !important;
    outline: none !important;
}

.form-control.search-input:focus + .search-icon {
    color: var(--neutral-300) !important; 
    display: block !important;
}

.search-container .form-control.search-input::placeholder {
    color: var(--neutral-300) !important;
    opacity: 1 !important;
}

</style>
<h1 class="page-title">Data Asisten Praktikum</h1>
<div class="search-container mb-3 position-relative">
    <input type="text" class="form-control search-input" placeholder="Cari Mata Kuliah, Nama, atau Kelas..." aria-label="Search">
    <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
</div>


<div id="noResultsMessage" class="text-center mt-4" style="display: none;">
    <p class="text-muted">Tidak ada asisten praktikum untuk mata kuliah ini.</p>
</div>

@php
$columns = [
['key' => 'nim', 'label' => 'NIM'],
['key' => 'nama', 'label' => 'Nama'],
['key' => 'kelas', 'label' => 'Kelas Praktikum'],
['key' => 'jumlah', 'label' => 'Jumlah Mahasiswa'],
['key' => 'wa', 'label' => 'No. WA'],
['key' => 'ktp', 'label' => 'KTP'],
['key' => 'rekening', 'label' => 'No. Rek (Nama Bank)']
];
@endphp


<x-admin.data-table :columns="$columns" :data="$asprakFormatted" row-class="asprak-row" row-mk-id="mk_id"/>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    const rows = document.querySelectorAll('.asprak-row');
    const noResults = document.getElementById('noResultsMessage');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        let hasVisible = false;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
                hasVisible = true;
            } else {
                row.style.display = 'none';
            }
        });

        if (noResults) {
            noResults.style.display = hasVisible ? 'none' : 'block';
        }
    });
});
</script>
@endpush