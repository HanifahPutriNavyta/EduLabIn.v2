<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet" />

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
            background: #fff;
            border-radius: 0 0 8px 8px;
            padding: 0 0 24px 0;
            border: none;
            box-shadow: none;
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

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-compact navbar-dark fixed-top shadow-sm">
        <div class="container-fluid">
            <!-- Burger menu button -->
            <button class="border-0 bg-transparent p-0 me-2"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu">
                <img class="burger-icon" src="{{ asset('img/sidebar.png') }}" alt="Menu">
            </button>
            

            <!-- Centered logo -->
            <div class="mx-auto">
                @if(Route::has('home'))
                    <a class="navbar-brand" href="{{ route('home') }}">
                @else
                    <a class="navbar-brand" href="#">
                @endif
                    <img src="{{ asset('img/LogoEduLabIn.png') }}" alt="Edu LabIn">
                </a>
            </div>

            <!-- Profile toggle button -->
            <button class="profile-toggle border-0 bg-transparent p-0"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#profileMenu">
                <img src="{{ asset('img/profile.png') }}" alt="Profile">
            </button>
        </div>
    </nav>

    <!-- Sidebar Menu -->
    <div class="offcanvas offcanvas-start offcanvas-sidebar" tabindex="-1" id="sidebarMenu" data-bs-scroll="true" data-bs-backdrop="true">
        <div class="offcanvas-header sidebar-header">
            @if(Route::has('home'))
                <a href="{{ route('home') }}">
            @else
                <a href="#">
            @endif
            <img src="{{ asset('img/LogoEduLabIn.png') }}" alt="Edu LabIn" class="sidebar-logo" style="cursor:pointer;">
            </a>
        </div>
        <div class="sidebar-content">
            <div class="sidebar-description">
                Edu LabIn adalah sebuah website sistem informasi layanan terpadu yang memberikan kemudahan bagi seluruh pengguna layanan Laboratorium Pembelajaran Fakultas Informatika, Universitas Brawijaya.
            </div>

            <hr>

            @php
            $user = auth()->user();
            $isAsprak = $user && isset($user->role) && $user->role === 'asprak';
            $dashboardUrl = $isAsprak ? route('dashboard.indexAsprak') : route('dashboard');
            @endphp

            @guest
            @if(Route::has('home'))
                <a href="{{ route('home') }}" class="sidebar-menu-item text-decoration-none d-flex text-dark align-items-center">
            @else
                <a href="#" class="sidebar-menu-item text-decoration-none d-flex text-dark align-items-center">
            @endif
                <span class="menu-text">Dashboard</span>
                <img src="{{ asset('img/IconHome.png') }}" alt="Home" class="menu-icon">
            </a>
            @endguest

            @auth
            <a href="{{ $dashboardUrl }}" class="sidebar-menu-item text-decoration-none d-flex text-dark align-items-center">
                <span class="menu-text">Dashboard</span>
                <img src="{{ asset('img/IconHome.png') }}" alt="Home" class="menu-icon">
            </a>

            <hr>

            @if(Route::has('logout'))
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @else
                <form action="#" method="POST" class="d-inline">
            @endif
                @csrf
                <button type="submit" class="sidebar-menu-item border-0 bg-transparent w-100 text-start">
                    <span class="menu-text">Keluar</span>
                    <img src="{{ asset('img/IconLogout2.png') }}" alt="Logout" class="menu-icon">
                </button>
            </form>
            @endauth
        </div>
    </div>

    <!-- Profile Menu -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="profileMenu" data-bs-scroll="true" data-bs-backdrop="true">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Profil</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            @auth
            <div class="profile-section" style="background:#fff;border-radius:0 0 8px 8px;padding:0 0 24px 0;border:none;box-shadow:none;">
                <div class="d-flex align-items-center" style="padding:24px 16px 16px 16px;border-bottom:2px solid #174E8D;">
                    <div style="width:80px;height:80px;border-radius:50%;border:3px solid #174E8D;overflow:hidden;display:flex;align-items:center;justify-content:center;background:#f3f6fa;">
                        @if(auth()->user()->profil && auth()->user()->profil->foto_path)
                        <img src="{{ asset('storage/' . auth()->user()->profil->foto_path) }}" alt="Foto Profil" style="width:100%;height:100%;object-fit:cover;">
                        @else
                        <img src="{{ asset('img/default-avatar.svg') }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                        @endif
                    </div>
                    <div class="ms-3" style="flex:1;">
                        <div style="font-weight:600;color:#174E8D;font-size:1.1rem;">
                            {{ strtoupper(auth()->user()->role->role_name) }} UB
                        </div>
                        <div style="font-size:1rem;color:#333;">{{ auth()->user()->profil->no_identitas ?? '-' }}</div>
                        <div style="font-size:0.95rem;color:#174E8D;font-weight:600;">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                </div>
                <div style="padding:12px 16px;border-bottom:1px solid #b0b0b0;">
                    <div style="font-size:0.85rem;color:#b0b0b0;">Nama</div>
                    <div style="font-size:1rem;color:#174E8D;font-weight:500;">
                        {{ auth()->user()->profil->nama_lengkap ?? auth()->user()->username }}
                    </div>
                </div>
                {{-- Data tambahan, tetap tampilkan jika ada --}}
                <div style="padding:12px 16px;border-bottom:1px solid #b0b0b0;">
                    <div style="font-size:0.85rem;color:#b0b0b0;">Prodi</div>
                    <div style="font-size:1rem;color:#174E8D;font-weight:500;">
                        {{ auth()->user()->profil->program_studi ?? '-' }}
                    </div>
                </div>
                <div style="padding:12px 16px;border-bottom:1px solid #b0b0b0;">
                    <div style="font-size:0.85rem;color:#b0b0b0;">Fakultas</div>
                    <div style="font-size:1rem;color:#174E8D;font-weight:500;">
                        {{ auth()->user()->profil->fakultas ?? '-' }}
                    </div>
                </div>
                <div style="padding:12px 16px;">
                    <div style="font-size:0.85rem;color:#b0b0b0;">Departemen</div>
                    <div style="font-size:1rem;color:#174E8D;font-weight:500;">
                        {{ auth()->user()->profil->departemen ?? '-' }}
                    </div>
                </div>
            </div>
            @else
            <div class="text-center text-muted">Silakan login untuk melihat profil.</div>
            @endauth
        </div>
    </div>

    <!-- Overlay for sidebar -->


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ensure only one offcanvas is open and keep exactly one correct backdrop
        (function() {
            const sidebar = document.getElementById('sidebarMenu');
            const profile = document.getElementById('profileMenu');

            function closeOther(current) {
                [sidebar, profile].forEach(el => {
                    if (!el || el === current) return;
                    const inst = bootstrap.Offcanvas.getOrCreateInstance(el);
                    inst.hide();
                });
            }

            function dedupeBackdrops() {
                const backdrops = Array.from(document.querySelectorAll('.offcanvas-backdrop'));
                if (backdrops.length <= 1) return;
                const shown = backdrops.filter(b => b.classList.contains('show'));
                const keep = (shown.length ? shown[shown.length - 1] : backdrops[backdrops.length - 1]);
                backdrops.forEach(b => { if (b !== keep) b.remove(); });
            }

            function cleanupIfNoneOpen() {
                // Only clean when no offcanvas is currently shown
                if (!document.querySelector('.offcanvas.show')) {
                    document.querySelectorAll('.offcanvas-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }
            }

            [sidebar, profile].forEach(el => {
                if (!el) return;
                el.addEventListener('show.bs.offcanvas', () => {
                    // Just close the other; let Bootstrap create the backdrop
                    closeOther(el);
                });
                el.addEventListener('shown.bs.offcanvas', () => {
                    // After shown, collapse any duplicate backdrops
                    dedupeBackdrops();
                });
                el.addEventListener('hidden.bs.offcanvas', () => {
                    // Allow Bootstrap to update DOM, then cleanup only if none open
                    setTimeout(() => {
                        dedupeBackdrops();
                        cleanupIfNoneOpen();
                    }, 10);
                });
            });
        })();
    </script>


</body>


</html>