<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BeritaAcara;
use App\Models\Asprak;
use App\Models\KelasPraktikum;
use Illuminate\Support\Str;

class BeritaAcaraSeeder extends Seeder
{
    public function run(): void
    {
        $asprak = Asprak::first();
        $kelas = KelasPraktikum::first();
        if (!$asprak || !$kelas) {
            return; // skip if no asprak or kelas
        }
        $data = [
            [
                'asprak_id' => $asprak->asprak_id,
                'kelas_id' => $kelas->kelas_id,
                'tanggal_kegiatan' => '2026-04-24',
                'deskripsi_kegiatan' => 'Materi tentang bumi itu datar',
                'judul' => 'Pertemuan 1',
                'tipe_pertemuan' => 'luring',
                'upload_berita_acara' => 'berita1.pdf',
                'upload_bukti_pertemuan' => 'bukti1.jpg',
                // file_path removed
                'status' => true,
            ],
            [
                'asprak_id' => $asprak->asprak_id,
                'kelas_id' => $kelas->kelas_id,
                'tanggal_kegiatan' => '2026-04-25',
                'deskripsi_kegiatan' => 'Materi tentang jaringan komputer',
                'judul' => 'Pertemuan 2',
                'tipe_pertemuan' => 'daring',
                'upload_berita_acara' => 'berita2.pdf',
                'upload_bukti_pertemuan' => 'bukti2.jpg',
                // file_path removed
                'status' => false,
            ],
            [
                'asprak_id' => $asprak->asprak_id,
                'kelas_id' => $kelas->kelas_id,
                'tanggal_kegiatan' => '2026-04-26',
                'deskripsi_kegiatan' => 'Materi tentang basis data',
                'judul' => 'Pertemuan 3',
                'tipe_pertemuan' => 'luring',
                'upload_berita_acara' => 'berita3.pdf',
                'upload_bukti_pertemuan' => 'bukti3.jpg',
                // file_path removed
                'status' => true,
            ],
        ];
        foreach ($data as $item) {
            BeritaAcara::firstOrCreate([
                'asprak_id' => $item['asprak_id'],
                'kelas_id' => $item['kelas_id'],
                'tanggal_kegiatan' => $item['tanggal_kegiatan'],
            ], $item);
        }
    }
} 