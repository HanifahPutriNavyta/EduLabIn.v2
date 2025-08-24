@extends('layouts.app')
@section('title', 'Detail Pengumuman')
@push('styles')
<style>

</style>
@endpush
@section('content')
<article class="pengumuman-detail">
    <h1 class="detail-pengumuman-title">{{ $pengumuman->judul }}</h1>
    
    <div class="pengumuman-image-container text-center mb-3">
        @if($pengumuman->gambar)
            <img src="{{ Storage::url('pengumuman/' . $pengumuman->gambar) }}" alt="{{ $pengumuman->judul }}" class="pengumuman-image img-fluid" style="max-width:100%;height:auto;">
        @endif
    </div>

    <div class="pengumuman-text">
        @if($pengumuman->deskripsi)
            {!! $pengumuman->deskripsi !!}
        @else
            <p>Tidak ada deskripsi tersedia.</p>
        @endif
    </div>
</article>

@endsection
@push('scripts')

@endpush