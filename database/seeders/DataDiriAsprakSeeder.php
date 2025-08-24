<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DataDiriAsprakSeeder extends Seeder
{
    public function run()
    {
        DB::table('data_diri_aspraks')->insert([
            [
                'asprak_id' => 1,
                'kelas_id' => 1,
                'nama' => 'Budi Santoso',
                'nim' => '2021001',
                'nomor_ktp' => '1234567890123456',
                'nomor_whatsapp' => '081234567890',
                'nomor_rekening' => '1234567890',
                'jumlah_mahasiswa' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'asprak_id' => 2,
                'kelas_id' => 1,
                'nama' => 'Ani Wijaya',
                'nim' => '2021002',
                'nomor_ktp' => '2345678901234567',
                'nomor_whatsapp' => '081234567891',
                'nomor_rekening' => '2345678901',
                'jumlah_mahasiswa' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'asprak_id' => 3,
                'kelas_id' => 1,
                'nama' => 'Dewi Putri',
                'nim' => '2021003',
                'nomor_ktp' => '3456789012345678',
                'nomor_whatsapp' => '081234567892',
                'nomor_rekening' => '3456789012',
                'jumlah_mahasiswa' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 