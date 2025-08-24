@extends('layouts.app')

@section('title', 'Dashboard Asprak Kelas - ' . $kelas->nama_mk)
@push('styles')
<link href="{{ asset('css/asprak.css') }}" rel="stylesheet">
@endpush
@section('content')
<h1 class="judul-halaman">Dashboard</h1>
   
    <!-- Asisten Praktikum Menu -->
    <a href="{{ route('absensi-praktikan.index', $kelas->kelas_id) }}" class="text-decoration-none">
    <div class="menu-item">
      <img src="{{ asset('img/iconAbsensiPraktikan.png') }}" alt="Absensi Praktikan" class="menu-icon">
      <span>Absensi Praktikan</span>
    </div>
    </a>
    <a href="{{ route('data-diri-asprak.show', $kelas->kelas_id) }}" class="text-decoration-none">
        <div class="menu-item">
            <img src="{{ asset('img/iconDataDiri.png') }}" alt="Data Diri Asprak" class="menu-icon">
            <span>Data Diri Asisten Praktikum</span>
        </div>
    </a>

    <a href="{{ route('berita-acara.indexAsprak', $kelas->kelas_id) }}" class="text-decoration-none">
        <div class="menu-item">
            <img src="{{ asset('img/iconBeritaAcara.png') }}" alt="Berita Acara" class="menu-icon">
            <span>Berita Acara</span>
        </div>
    </a>

    <a href="{{ route('informasi-nilai-asprak.index', $kelas->kelas_id) }}" class="text-decoration-none">
        <div class="menu-item">
            <img src="{{ asset('img/IconInformasiNilai.png') }}" alt="Informasi Nilai" class="menu-icon">
            <span>Informasi Nilai</span>
        </div>
    </a>
@endsection