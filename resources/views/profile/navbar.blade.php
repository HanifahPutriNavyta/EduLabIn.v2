<link href="{{ asset('css/asprak.css') }}" rel="stylesheet">

@push('styles')
<style>
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 998;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .sidebar-menu {
        position: fixed;
        top: 0;
        left: -320px;
        width: 100%;
        max-width: 320px;
        height: 100vh;
        background-color: var(--based-50);
        z-index: 999;
        transition: left 0.3s ease;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 0 15px 15px 0;
        overflow-y: auto;
    }

    .sidebar-menu.active {
        left: 0;
    }

    .sidebar-header {
        padding: 20px;
        background-color: var(--primary);
        border-radius: 0 15px 0 0;
    }

    .sidebar-logo img {
        height: 40px;
    }

    .sidebar-close {
        background: none;
        border: none;
        color: var(--based-50);
        font-size: 24px;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.2s ease;
    }

    .sidebar-close:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar-content {
        padding: 20px 0;
    }

    .sidebar-nav-item {
        padding: 15px 20px;
        color: var(--neutral-600);
        text-decoration: none;
        transition: background-color 0.2s ease, color 0.2s ease;
        font-family: 'Montserrat', sans-serif;
        font-weight: 500;
    }

    .sidebar-nav-item:hover {
        background-color: var(--secondary);
        color: var(--based-50);
    }

    .sidebar-nav-item img {
        width: 24px;
        height: 24px;
        margin-right: 15px;
        filter: brightness(0) saturate(100%) invert(42%) sepia(7%) saturate(0%) hue-rotate(237deg) brightness(92%) contrast(89%);
        transition: filter 0.2s ease;
    }

    .sidebar-nav-item:hover img {
        filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(0%) hue-rotate(93deg) brightness(103%) contrast(103%);
    }

    .sidebar-nav-item span {
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .sidebar-menu {
            height: 100vh;
            border-radius: 0;
        }

        .sidebar-header {
            border-radius: 0;
        }
    }

    /* Body scroll lock when sidebar is open */
    body.sidebar-open {
        overflow: hidden;
    }

    .sidebar-description {
        background-color: var(--secondary);
        border-radius: 15px;
        margin: 16px 12px;
    }

    .sidebar-divider {
        border: 0;
        border-top: 1px solid;
        margin: 0;
    }
</style>
@endpush

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<div class="sidebar-menu" id="sidebarMenu">
    <div class="sidebar-header d-flex justify-content-between align-items-center">
        <div class="sidebar-logo">
            <img src="{{ asset('img/LogoEduLabIn.png') }}" alt="Logo">
        </div>
        <button class="sidebar-close" id="sidebarClose">
            <span>&times;</span>
        </button>
    </div>

    <!-- Description Section -->
    <div class="sidebar-description p-3">
        <p class="mb-0" style="font-size: 0.9rem; line-height: 1.4;">
            Edu LabIn adalah sebuah website sistem informasi layanan terpadu yang memberikan kemudahan bagi seluruh pengguna layanan Laboratorium Pembelajaran Fakultas Informatika, Universitas Brawijaya.
        </p>
    </div>

    <div class="sidebar-content">
        <nav class="sidebar-nav d-flex flex-column">
            <hr class="sidebar-divider my-0">
            <a href="{{ route('dashboard') }}" class="sidebar-nav-item d-flex align-items-center justify-content-between">
                <span>Dashboard</span>
                <img src="{{ asset('img/home.png') }}" alt="Dashboard">
            </a>
            <hr class="sidebar-divider my-0">
            <a href="#" class="sidebar-nav-item d-flex align-items-center justify-content-between">
                <span>Keluar</span>
                <img src="{{ asset('img/logout.png') }}" alt="Logout">
            </a>
        </nav>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="sidebar d-flex align-items-center" id="sidebarToggle" style="cursor: pointer;">
            <img src="{{ asset('img/sidebar.png') }}" alt="Sidebar">
        </div>
        <div class="logo d-flex align-items-center">
            <img src="{{ asset('img/LogoEduLabIn.png') }}" alt="Logo">
        </div>
        <div class="profile d-flex align-items-center">
            <img src="{{ asset('img/profile.png') }}" alt="Profile">
        </div>
    </div>
</nav>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarMenu = document.getElementById('sidebarMenu');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarClose = document.getElementById('sidebarClose');
    const body = document.body;

    sidebarToggle.addEventListener('click', function() {
        sidebarMenu.classList.add('active');
        sidebarOverlay.classList.add('active');
        body.classList.add('sidebar-open');
    });

    function closeSidebar() {
        sidebarMenu.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        body.classList.remove('sidebar-open');
    }

    sidebarClose.addEventListener('click', closeSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebarMenu.classList.contains('active')) {
            closeSidebar();
        }
    });
});
</script>
@endpush
