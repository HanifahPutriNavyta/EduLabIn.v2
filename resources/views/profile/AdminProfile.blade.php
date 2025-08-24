@extends('layouts.app')
@section('title', 'Profil - EduLabIn')

@push('styles')
    <style>
        .profile-container {
            width: 100%;
            position: relative;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            margin: 20px 0 20px 10px;
            text-align: left;
        }

        .profile-divider-top {
            height: 3px;
            background-color: var(--primary);
            margin-bottom: 20px;
        }

        .profile-section {
            width: 100%;
            position: relative;
            border-top: 4px solid #174E8D;
            border-bottom: 4px solid #174E8D;
            margin-bottom: 40px;
            margin-top: 80px;
            padding: 12px 20px;
        }

        .profile-header {
            display: flex;
            flex-direction: row;
            gap: 16px;
            align-items: center;
            position: relative;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid var(--primary);
            margin-right: 30px;
            object-fit: cover;
            background: linear-gradient(135deg, #a8e6cf 0%, #88d8c0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: -72px;
            z-index: 2;
            flex-shrink: 0;
        }

        .profile-avatar div {
            font-size: 3rem;
        }

        .profile-info {
            flex: 1;
            position: relative;
            margin-left: 20vw;
            z-index: 2;
        }

        .profile-info h2 {
            color: var(--neutral-600);
            font-size: 1.5rem;
            font-weight: 400;
            margin-bottom: 8px;
            margin-top: 0;
        }

        .profile-info .profile-role {
            color: var(--neutral-600);
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .profile-info .profile-email {
            color: var(--neutral-600);
            font-size: 1rem;
            font-weight: 500;
        }

        .form-section {
            margin-top: 40px;
        }

        .form-group {
            margin: 0 20px;
        }

        .form-label {
            color: #B0B0B0;
            font-size: 1rem;
            font-weight: 400;
            display: block;
        }

        .form-display-text {
            color: var(--primary);
            font-size: 1.1rem;
            font-weight: 500;
            border-bottom: 2px solid #ddd;
            margin-bottom: 0;
        }

        @media (max-width: 640px) {
            .profile-info {
                margin-left: 40%;
            }
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary);
            color: white;
            text-decoration: none;
        }
    </style>
@endpush


@section('content')
    <main>
        <div class="profile-container">
            <h1 class="page-title">Dashboard</h1>
            <div class="profile-section">
                <div class="profile-header">
                    <div class="profile-avatar">
                        @if($user->profil && $user->profil->foto_path)
                            <img src="{{ asset('storage/' . $user->profil->foto_path) }}" alt="Profile Photo" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="font-size: 3rem;">👨‍💻</div>
                        @endif
                    </div>
                    <div class="profile-info">
                        <h2>{{ $user->profil->nama_lengkap ?? $user->username }}</h2>
                        <div class="profile-role">{{ $user->username }}</div>
                        <div class="profile-email">{{ $user->email }}</div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-group">
                    <label for="name" class="form-label my-0">Nama</label>
                    <p class="form-display-text">{{ $user->profil->nama_lengkap ?? 'Nama belum diisi' }}</p>
                </div>
                
                <div class="form-group">
                    <label for="no_identitas" class="form-label my-0">No. Identitas</label>
                    <p class="form-display-text">{{ $user->profil->no_identitas ?? 'No. identitas belum diisi' }}</p>
                </div>
                
                <div class="form-group">
                    <label for="fakultas" class="form-label my-0">Fakultas</label>
                    <p class="form-display-text">{{ $user->profil->fakultas ?? 'Fakultas belum diisi' }}</p>
                </div>
                
                <div class="form-group">
                    <label for="departemen" class="form-label my-0">Departemen</label>
                    <p class="form-display-text">{{ $user->profil->departemen ?? 'Departemen belum diisi' }}</p>
                </div>
                
                <div class="form-group">
                    <label for="program_studi" class="form-label my-0">Program Studi</label>
                    <p class="form-display-text">{{ $user->profil->program_studi ?? 'Program studi belum diisi' }}</p>
                </div>
                
                <div class="form-group">
                    <label for="no_whatsapp" class="form-label my-0">No. WhatsApp</label>
                    <p class="form-display-text">{{ $user->profil->no_whatsapp ?? 'No. WhatsApp belum diisi' }}</p>
                </div>
                
                <div class="form-group" style="margin-top: 30px;">
                    @if (Route::has('profile.edit'))
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
                    @else
                        <button class="btn btn-primary" disabled>Profil (route missing)</button>
                    @endif
                </div>
            </div>
        </div>
    </main>
@endsection
