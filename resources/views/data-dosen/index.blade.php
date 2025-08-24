@extends('layouts.app')
@section('title', 'Data Dosen Praktikum - EduLabIn')

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
@section('content')
    <main class="container py-4">
        <h1 class="page-title">Data Dosen Praktikum</h1>

        <!-- Search Bar -->
        <div class="search-container mb-3 position-relative">
            <input type="text" class="form-control search-input" placeholder="Cari dosen, mata kuliah, atau kelas..."
                aria-label="Search">
            <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
        </div>

        <x-admin.data-table :columns="$columns" :data="$data" />

        <a href="{{ route('data-dosen.create') }}" class="text-decoration-none">
            <button class="fab-button" onclick="openAddClassDialog()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="#222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                
            </button>
        </a>
    </main>
@endsection

@push('styles')
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
            z-index: 1000;
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            const tableRows = document.querySelectorAll('table tbody tr');
            const dataTable = document.querySelector('table');
            let noResultsRow = null;

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    let hasVisible = false;

                    tableRows.forEach(row => {
                        const rowText = row.textContent.toLowerCase();
                        const shouldShow = rowText.includes(searchTerm);
                        row.style.display = shouldShow ? '' : 'none';
                        if (shouldShow) hasVisible = true;
                    });

                    // Tampilkan pesan jika tidak ada hasil
                    if (!hasVisible) {
                        if (!noResultsRow) {
                            noResultsRow = document.createElement('tr');
                            const td = document.createElement('td');
                            td.colSpan = dataTable.querySelectorAll('thead th').length;
                            td.className = 'text-center text-muted';
                            td.textContent = 'Tidak ada data dosen yang ditemukan.';
                            noResultsRow.appendChild(td);
                            dataTable.querySelector('tbody').appendChild(noResultsRow);
                        }
                    } else if (noResultsRow) {
                        noResultsRow.remove();
                        noResultsRow = null;
                    }
                });
            }
        });
    </script>
@endpush