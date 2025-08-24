<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::firstOrCreate(
        //     ['username' => 'testuser'],
        //     [
        //     'email' => 'test@example.com',
        //         'password' => Hash::make('password'),
        //         'role_id' => 1,
        //     ]
        // );

        $this->call([
            RoleSeeder::class,
            MataKuliahSeeder::class,
            DosenSeeder::class,
            KelasPraktikumSeeder::class,
            AsprakSeeder::class,    
            LaboranSeeder::class,
            BeritaAcaraSeeder::class,
            NilaiPraktikumSeeder::class,
            PengumumanSeeder::class,
            PendaftaranAsprakSeeder::class,
            
        ]);
    }
}
