<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataKuliah;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        MataKuliah::updateOrCreate(
            ['mk_id' => 1],
            ['nama_mk' => 'Pemrograman Web']
        );
    }
} 