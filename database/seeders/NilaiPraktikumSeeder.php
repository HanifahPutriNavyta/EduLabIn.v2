<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NilaiPraktikum;
use App\Models\Asprak;
use App\Models\KelasPraktikum;

class NilaiPraktikumSeeder extends Seeder
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
                'kelas_id' => $kelas->kelas_id,
                'asprak_id' => $asprak->asprak_id,
                'judul' => 'Nilai Praktikum 1',
                'tanggal' => '2026-04-24',
                'deskripsi' => 'Deskripsi nilai praktikum 1',
                'upload_file' => 'nilai1.pdf',
            ],
            [
                'kelas_id' => $kelas->kelas_id,
                'asprak_id' => $asprak->asprak_id,
                'judul' => 'Nilai Praktikum 2',
                'tanggal' => '2026-04-25',
                'deskripsi' => 'Deskripsi nilai praktikum 2',
                'upload_file' => 'nilai2.pdf',
            ],
            [
                'kelas_id' => $kelas->kelas_id,
                'asprak_id' => $asprak->asprak_id,
                'judul' => 'Nilai Praktikum 3',
                'tanggal' => '2026-04-26',
                'deskripsi' => 'Deskripsi nilai praktikum 3',
                'upload_file' => 'nilai3.pdf',
            ],
        ];
        foreach ($data as $item) {
            NilaiPraktikum::firstOrCreate([
                'kelas_id' => $item['kelas_id'],
                'asprak_id' => $item['asprak_id'],
                'judul' => $item['judul'],
            ], $item);
        }
    }
} 