<?php

use App\Http\Controllers\AbsensiPraktikanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasPraktikumController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\DataAsprakController;
use App\Http\Controllers\DataCalonAsprakController;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\CalonAsprakController;
use App\Http\Controllers\InformasiNilaiController;
use App\Http\Controllers\DataDiriAsprakController;
use App\Http\Controllers\DataDosenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatakuliahController;

// Authentication Routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
   
    Route::get('dosen-berita-acara/{beritaAcara}/download', [BeritaAcaraController::class, 'download'])
        ->name('berita-acara.download.public');
    Route::get('dosen-berita-acara/{beritaAcara}/download-bukti', [BeritaAcaraController::class, 'downloadBukti'])
        ->name('berita-acara.download-bukti.public');

    // Laboran Routes
    Route::middleware(['role:laboran'])->group(function () {
        // Pengumuman

        Route::get('pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.laboran.index');
        Route::put('pengumuman/update/{pengumuman}', [PengumumanController::class, 'update'])->name('pengumuman.laboran.update');
        Route::post('pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.laboran.store');
        Route::put('pengumuman/toggle-status/{pengumuman}', [PengumumanController::class, 'toggleStatus'])->name('pengumuman.laboran.toggleStatus');
        Route::delete('pengumuman/{pengumuman}', [PengumumanController::class, 'destroy'])->name('pengumuman.laboran.destroy');

        // Data Asprak
        Route::get('data-asprak', [DataAsprakController::class, 'index'])->name('data-asprak.index');
        Route::get('data-asprak/{asprak}', [DataAsprakController::class, 'show'])->name('data-asprak.show');

        //kelas praktikum laboran
        Route::get('data-kelas-praktikum/', [KelasPraktikumController::class, 'index'])->name('kelas-praktikum.laboran.index');
        Route::get('data-kelas-praktikum/{kelasPraktikum}', [KelasPraktikumController::class, 'show'])->name('kelas-praktikum.laboran.show');
        Route::get('data-kelas-praktikum/create', [KelasPraktikumController::class, 'create'])->name('kelas-praktikum.laboran.create');
        Route::post('data-kelas-praktikum', [KelasPraktikumController::class, 'store'])->name('kelas-praktikum.laboran.store');
        Route::get('data-kelas-praktikum/{kelasPraktikum}/edit', [KelasPraktikumController::class, 'edit'])->name('kelas-praktikum.laboran.edit');
        Route::put('data-kelas-praktikum/{kelasPraktikum}', [KelasPraktikumController::class, 'update'])->name('kelas-praktikum.laboran.update');
        Route::delete('data-kelas-praktikum/{kelasPraktikum}', [KelasPraktikumController::class, 'destroy'])->name('kelas-praktikum.laboran.destroy');

        // Mata Kuliah
        Route::get('mata-kuliah', [MatakuliahController::class, 'index'])->name('matakuliah.laboran.index');
        Route::post('mata-kuliah', [MatakuliahController::class, 'store'])->name('matakuliah.laboran.store');
        Route::get('mata-kuliah/{matakuliah}/edit', [MatakuliahController::class, 'edit'])->name('matakuliah.laboran.edit');
    // Toggle status endpoint (simple toggle to avoid full-update validation)
        Route::put('mata-kuliah/{matakuliah}/status', [MatakuliahController::class, 'toggleStatus'])->name('matakuliah.laboran.toggleStatus');
        Route::put('mata-kuliah/{matakuliah}', [MatakuliahController::class, 'update'])->name('matakuliah.laboran.update');
        Route::delete('mata-kuliah/{matakuliah}', [MatakuliahController::class, 'destroy'])->name('matakuliah.laboran.destroy');

        // Data Calon Asprak
        Route::get('data-calon-asprak', [DataCalonAsprakController::class, 'index'])->name('data-calon-asprak.index');
        
        // Data Dosen
        Route::get('data-dosen', [DataDosenController::class, 'index'])->name('data-dosen.index');
        Route::get('data-dosen/create', [DataDosenController::class, 'create'])->name('data-dosen.create');
        Route::post('data-dosen', [DataDosenController::class, 'store'])->name('data-dosen.store');
       
       

    });

    // Dosen Routes
    Route::middleware(['role:dosen'])->group(function () {
        // Berita Acara
        Route::get('berita-acara/dosen', [BeritaAcaraController::class, 'indexDosen'])->name('berita-acara.indexDosen');
        Route::get('berita-acara/dosen/{kelas}', [BeritaAcaraController::class, 'showKelas'])->name('berita-acara.showKelas');
        Route::get('berita-acara/dosen/{beritaAcara}', [BeritaAcaraController::class, 'show'])->name('berita-acara.show');
        Route::get('berita-acara/dosen/{beritaAcara}/download', [BeritaAcaraController::class, 'download'])->name('berita-acara.download');
        Route::get('berita-acara/dosen/{beritaAcara}/download-bukti', [BeritaAcaraController::class, 'downloadBukti'])->name('berita-acara.download-bukti');
        // Informasi Nilai
        Route::get('informasi-nilai', [InformasiNilaiController::class, 'index'])->name('informasi-nilai.index');
        Route::get('informasi-nilai/{matkul}', [InformasiNilaiController::class, 'show'])->name('informasi-nilai.show');
        Route::get('informasi-nilai/{nilai}/download', [InformasiNilaiController::class, 'download'])->name('informasi-nilai.download');
    });

    // Asprak Routes
    Route::middleware(['role:asprak'])->group(function () {
        // Dashboard
        Route::get('dashboard/asprak', [DashboardController::class, 'indexAsprak'])->name('dashboard.indexAsprak');
        Route::get('dashboard/asprak/kelas/{kode}', [DashboardController::class, 'indexAsprakKelas'])->name('dashboard.indexAsprakKelas');
        
        // Kelas Praktikum
        Route::get('/kelas-praktikum', [KelasPraktikumController::class, 'index'])->name('kelas-praktikum.index');
        Route::get('/kelas-praktikum/{id}', [KelasPraktikumController::class, 'show'])->name('kelas-praktikum.show');
        Route::get('/kelas-praktikum-asprak', [KelasPraktikumController::class, 'asprak'])->name('kelas-praktikum.asprak');
        Route::post('/kelas-praktikum/enroll', [KelasPraktikumController::class, 'enrollKelas'])->name('kelas-praktikum.enroll');
        
        // Data Absensi Praktikan
        Route::get('absensi-praktikan/{kelas_id}', [AbsensiPraktikanController::class, 'index'])->name('absensi-praktikan.index');
        Route::get('absensi-praktikan/detail/{absensiPraktikan}', [AbsensiPraktikanController::class, 'show'])->name('absensi-praktikan.show');
        Route::get('absensi-praktikan/asprak/create/{kelas_id}', [AbsensiPraktikanController::class, 'create'])->name('absensi-praktikan.create');
        Route::post('absensi-praktikan', [AbsensiPraktikanController::class, 'store'])->name('absensi-praktikan.store');
        Route::get('absensi-praktikan/edit/{absensiPraktikan}', [AbsensiPraktikanController::class, 'edit'])->name('absensi-praktikan.edit');
        Route::put('absensi-praktikan/{absensiPraktikan}', [AbsensiPraktikanController::class, 'update'])->name('absensi-praktikan.update');
        Route::delete('absensi-praktikan/{absensiPraktikan}', [AbsensiPraktikanController::class, 'destroy'])->name('absensi-praktikan.destroy');   
        
        // Data Diri Asprak
        Route::get('data-diri-asprak/{kelas_id}', [DataDiriAsprakController::class, 'show'])->name('data-diri-asprak.show');
        Route::post('data-diri-asprak/update/{kelas_id}', [DataDiriAsprakController::class, 'updateData'])->name('data-diri-asprak.updateData');
        // Route::get('data-diri-asprak/edit', [DataDiriAsprakController::class, 'edit'])->name('data-diri-asprak.edit');
        Route::put('data-diri-asprak', [DataDiriAsprakController::class, 'update'])->name('data-diri-asprak.update');
        

        // Berita Acara
        Route::get('berita-acara/{kelas_id}', [BeritaAcaraController::class, 'indexAsprak'])->name('berita-acara.indexAsprak');
        Route::get('berita-acara/create/{kelas_id}', [BeritaAcaraController::class, 'create'])->name('berita-acara.create');
        Route::post('berita-acara', [BeritaAcaraController::class, 'store'])->name('berita-acara.store');
        Route::get('berita-acara/{beritaAcara}/edit', [BeritaAcaraController::class, 'edit'])->name('berita-acara.edit');
        Route::put('berita-acara/{beritaAcara}', [BeritaAcaraController::class, 'update'])->name('berita-acara.update');
        Route::delete('berita-acara/{beritaAcara}', [BeritaAcaraController::class, 'destroy'])->name('berita-acara.destroy');
    // Note: 'dosen-berita-acara' download routes are defined with public auth access
    // above to avoid role:asprak middleware blocking dosen users.

        // Informasi Nilai
        Route::get('informasi-nilai/asprak/{kelas_id}', [InformasiNilaiController::class, 'indexAsprak'])->name('informasi-nilai-asprak.index');
        Route::get('informasi-nilai/asprak/create/{kelas_id}', [InformasiNilaiController::class, 'createAsprak'])->name('informasi-nilai-asprak.create');
        Route::post('informasi-nilai/asprak', [InformasiNilaiController::class, 'storeAsprak'])->name('informasi-nilai-asprak.store');
        Route::get('informasi-nilai/asprak/{kelas_id}/edit/{nilai}', [InformasiNilaiController::class, 'editAsprak'])->name('informasi-nilai-asprak.edit');
        Route::put('informasi-nilai/asprak/{kelas_id}/{nilai}', [InformasiNilaiController::class, 'updateAsprak'])->name('informasi-nilai-asprak.update');
        Route::delete('informasi-nilai/asprak/{kelas_id}/{nilai}', [InformasiNilaiController::class, 'destroyAsprak'])->name('informasi-nilai-asprak.destroy');
    });

});

// Calon Asprak Routes

Route::get('calon-asprak', [CalonAsprakController::class, 'DashboardCasprak'])->name('calonAsprak.DashboardCasprak');
Route::get('calon-asprak/pengumuman', [CalonAsprakController::class, 'PengumumanCasprak'])->name('calonAsprak.PengumumanCasprak');
Route::get('calon-asprak/pengumuman/{id}', [CalonAsprakController::class, 'DetailPengumumanCasprak'])->name('calonAsprak.DetailPengumumanCasprak');
Route::get('calon-asprak/pendaftaran', [CalonAsprakController::class, 'PendaftaranCasprak'])->name('calonAsprak.PendaftaranCasprak');
Route::get('calon-asprak/pendaftaran/{id}', [CalonAsprakController::class, 'FormPendaftaranCasprak'])->name('calonAsprak.FormPendaftaranCasprak');
Route::post('calon-asprak/pendaftaran', [CalonAsprakController::class, 'SubmitPendaftaran'])->name('calonAsprak.SubmitPendaftaran');

// Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');