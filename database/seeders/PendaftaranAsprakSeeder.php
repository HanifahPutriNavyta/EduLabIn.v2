<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PendaftaranAsprak;
use App\Models\Matakuliah;

class PendaftaranAsprakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing kelas praktikum
        $matakuliahs = Matakuliah::all();

        if ($matakuliahs->isEmpty()) {
            $this->command->warn('No Matakuliah found. Please run MatakuliahSeeder first.');
            return;
        }

        foreach ($matakuliahs as $matakuliah) {
            PendaftaranAsprak::updateOrCreate(
                [
                    'mk_id' => $matakuliah->mk_id,
                ],
                [
                    'kuota' => rand(5, 15),
                    'ketentuan' => "1. Minimal IPK 3.0\n2. Sudah lulus mata kuliah ini\n3. Aktif dalam organisasi kampus\n4. Memiliki kemampuan komunikasi yang baik\n5. Bersedia mengikuti pelatihan",
                    // 'status' => true
                ]
            );
        }

        $this->command->info('PendaftaranAsprak seeded successfully!');
    }
}
