@extends('layouts.app')

@section('title', 'Dashboard - EduLabIn')

@push('styles')
<link href="{{ asset('css/dosen.css') }}" rel="stylesheet">
<style>

</style>
@endpush

@section('content')
<h1 class="judul-halaman">Dashboard</h1>

@if(Auth::user()->role->role_name === 'dosen')
<!-- Dosen Menu -->
<a href="{{ route('berita-acara.indexDosen') }}" class="text-decoration-none">
    <div class="menu-item">
        <img src="{{ asset('img/iconBeritaAcara.png') }}" alt="Berita Acara" class="menu-icon">
        <span>Berita Acara</span>
    </div>
</a>

<a href="{{ route('informasi-nilai.index') }}" class="text-decoration-none">
    <div class="menu-item">
        <img src="{{ asset('img/IconInformasiNilai.png') }}" alt="Informasi Nilai" class="menu-icon">
        <span>Informasi Nilai</span>
    </div>
</a>
@endif

@if(Auth::user()->role->role_name === 'laboran')
<!-- Laboran Menu -->
<a href="{{ route('kelas-praktikum.laboran.index') }}" class="text-decoration-none">
    <div class="menu-item">
        <img src="{{ asset('img/iconKelasPraktikum.png') }}" alt="Kelas Praktikum" class="menu-icon">
        <span>Kelas Praktikum</span>
    </div>
</a>

<a href="{{ route('matakuliah.laboran.index') }}" class="text-decoration-none">
    <div class="menu-item">
        <img src="{{ asset('img/iconPendataan.png') }}" alt="Mata Kuliah" class="menu-icon">
        <span>Mata Kuliah</span>
    </div>
</a>

<a href="{{ route('pengumuman.laboran.index') }}" class="text-decoration-none">
    <div class="menu-item">
        <img src="{{ asset('img/IconPengumuman.png') }}" alt="Pengumuman" class="menu-icon">
        <span>Pengumuman</span>
    </div>
</a>

<a href="{{ route('data-asprak.index') }}" class="text-decoration-none">
    <div class="menu-item">
        <img src="{{ asset('img/iconDataAsistenPraktikum.png') }}" alt="Data Asisten Praktikum" class="menu-icon">
        <span>Data Asisten Praktikum</span>
    </div>
</a>

<a href="{{ route('data-calon-asprak.index') }}" class="text-decoration-none">
    <div class="menu-item">
        <img src="{{ asset('img/iconDataCalonAsprak.png') }}" alt="Data Calon Asprak" class="menu-icon">
        <span>Data Calon Asprak</span>
    </div>
</a>

<a href="{{ route('data-dosen.index') }}" class="text-decoration-none">
    <div class="menu-item">
        <img src="{{ asset('img/iconDataCalonAsprak.png') }}" alt="Data Dosen Praktikum" class="menu-icon">
        <span>Data Dosen Praktikum</span>
    </div>
</a>
@endif


@endsection