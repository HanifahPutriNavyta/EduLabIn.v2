<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KelasPraktikum;
use App\Models\MataKuliah;
use App\Models\Dosen;

class KelasPraktikumSeeder extends Seeder
{
    public function run(): void
    {
        $mataKuliah = MataKuliah::first();
        $dosen = Dosen::first();
        if ($mataKuliah && $dosen) {
            KelasPraktikum::firstOrCreate(
                ['kode_kelas' => 'Kelas A'],
                [
                    'mk_id' => $mataKuliah->mk_id,
                    'dosen_id' => $dosen->dosen_id,
                    'kode_enroll' => 'MK001A',
                    'status' => 1
                ],
                ['kode_kelas' => 'Kelas A'],
                [
                    'mk_id' => $mataKuliah->mk_id,
                    'dosen_id' => $dosen->dosen_id,
                    'kode_enroll' => 'MK004A',
                    'status' => 1
                ]
            );
        }
    }
} 