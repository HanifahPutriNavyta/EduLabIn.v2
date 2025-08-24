@extends('layouts.app')
@section('title', 'Edit Profil - EduLabIn')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/asprak.css') }}">
<style>
    .profile-edit-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #333;
    }
    
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .btn-primary {
        background-color: var(--primary);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        margin-left: 10px;
    }
</style>
@endpush

@section('content')
<main class="profile-edit-container">
    <h1>Edit Profil</h1>
    
    @if(Route::has('profile.update'))
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @else
    <form action="#" method="POST" enctype="multipart/form-data">
    @endif
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" 
                   value="{{ old('username', $user->username) }}" required>
        </div>
        
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="{{ old('email', $user->email) }}" required>
        </div>
        
        <div class="form-group">
            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                   value="{{ old('nama_lengkap', $user->profil->nama_lengkap ?? '') }}" required>
        </div>
        
        <div class="form-group">
            <label for="no_identitas" class="form-label">No. Identitas</label>
            <input type="text" class="form-control" id="no_identitas" name="no_identitas" 
                   value="{{ old('no_identitas', $user->profil->no_identitas ?? '') }}">
        </div>
        
        <div class="form-group">
            <label for="fakultas" class="form-label">Fakultas</label>
            <input type="text" class="form-control" id="fakultas" name="fakultas" 
                   value="{{ old('fakultas', $user->profil->fakultas ?? '') }}">
        </div>
        
        <div class="form-group">
            <label for="departemen" class="form-label">Departemen</label>
            <input type="text" class="form-control" id="departemen" name="departemen" 
                   value="{{ old('departemen', $user->profil->departemen ?? '') }}">
        </div>
        
        <div class="form-group">
            <label for="program_studi" class="form-label">Program Studi</label>
            <input type="text" class="form-control" id="program_studi" name="program_studi" 
                   value="{{ old('program_studi', $user->profil->program_studi ?? '') }}">
        </div>
        
        <div class="form-group">
            <label for="status_akademik" class="form-label">Status Akademik</label>
            <select class="form-control" id="status_akademik" name="status_akademik">
                <option value="">Pilih Status</option>
                <option value="Dosen" {{ old('status_akademik', $user->profil->status_akademik ?? '') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="Mahasiswa" {{ old('status_akademik', $user->profil->status_akademik ?? '') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                <option value="Staff" {{ old('status_akademik', $user->profil->status_akademik ?? '') == 'Staff' ? 'selected' : '' }}>Staff</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="no_whatsapp" class="form-label">No. WhatsApp</label>
            <input type="text" class="form-control" id="no_whatsapp" name="no_whatsapp" 
                   value="{{ old('no_whatsapp', $user->profil->no_whatsapp ?? '') }}">
        </div>
        
        <div class="form-group">
            <label for="foto_profil" class="form-label">Foto Profil</label>
            <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/*">
            @if($user->profil && $user->profil->foto_path)
                <small class="text-muted">Foto saat ini: {{ basename($user->profil->foto_path) }}</small>
            @endif
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn-primary">Update Profil</button>
            @if(Route::has('profile.show'))
                <a href="{{ route('profile.show') }}" class="btn-secondary">Kembali</a>
            @else
                <a href="{{ url('/') }}" class="btn-secondary">Kembali</a>
            @endif
        </div>
    </form>
</main>
@endsection
