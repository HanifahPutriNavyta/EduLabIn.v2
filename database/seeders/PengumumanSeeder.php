<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengumuman;
use Carbon\Carbon;

class PengumumanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengumumans = [
            [
                'created_by' => 1, // Assuming user_id 1 exists
                'judul' => 'Pendaftaran Asisten Praktikum Semester Genap 2024/2025',
                'deskripsi' => 'Pendaftaran asisten praktikum untuk semester genap 2024/2025 telah dibuka. Silakan daftar melalui sistem yang telah disediakan. Pendaftaran akan ditutup pada tanggal 15 Januari 2025.',
                'gambar' => 'pengumuman/pengumuman1.jpg',
                'status' => true
            ],
            [
                'created_by' => 1,
                'judul' => 'Jadwal Pelatihan Asisten Praktikum',
                'deskripsi' => 'Pelatihan asisten praktikum akan dilaksanakan pada tanggal 20-22 Januari 2025. Semua asisten praktikum yang telah diterima wajib mengikuti pelatihan ini.',
                'gambar' => 'pengumuman/pengumuman2.jpg',
                'status' => true
            ],
            [
                'created_by' => 1,
                'judul' => 'Pengumuman Hasil Seleksi Asisten Praktikum',
                'deskripsi' => 'Hasil seleksi asisten praktikum untuk mata kuliah Pemrograman Web telah diumumkan. Silakan cek status pendaftaran Anda di dashboard.',
                'gambar' => 'pengumuman/pengumuman3.jpg',
                'status' => true
            ]
        ];

        foreach ($pengumumans as $pengumuman) {
            Pengumuman::updateOrCreate([
                'judul' => $pengumuman['judul'],
            ],$pengumuman);
        }

        $this->command->info('Pengumuman seeded successfully!');
    }
}
