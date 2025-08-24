@extends('layouts.app')

@section('title', 'Informasi Nilai')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/dosen.css') }}">
@endpush
@section('content')
<h1 class="judul-halaman">Informasi Nilai</h1>

<!-- Filter -->
<div class="dropdown-container">
    <select class="form-select shadow-sm" aria-label="Cari Mata Kuliah">
        <option selected>Cari Mata Kuliah</option>
        <option value="1">Jaringan Komputer Dasar</option>
        <option value="2">Basis Data</option>
        <option value="3">Pemrograman Web</option>
    </select>
    <img class="dropdown-arrow" src="/img/dropdown.png" alt="Dropdown">
</div>

<div class="line-div"></div>

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