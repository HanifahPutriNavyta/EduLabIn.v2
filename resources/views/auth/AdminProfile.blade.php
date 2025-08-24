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
    </style>
@endpush

@include('partials.navbar')

@section('content')
    <main>
        <div class="profile-container">
            <h1 class="page-title">Dashboard</h1>
            <div class="profile-section">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <div style="font-size: 3rem;">👨‍💻</div>
                    </div>
                    <div class="profile-info">
                        <h2>Mahasiswa UB</h2>
                        <div class="profile-role">NIP</div>
                        <div class="profile-email">Email Laboran</div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-group">
                    <label for="name" class="form-label my-0">Nama</label>
                    <p class="form-display-text">Nama Laboran</p>
                </div>
            </div>
        </div>
    </main>
@endsection
