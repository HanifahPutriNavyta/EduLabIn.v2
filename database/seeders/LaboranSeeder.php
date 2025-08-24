<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Laboran;
use App\Models\ProfilPengguna;
use Illuminate\Support\Facades\Hash;

class LaboranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laborans = [
            [
            'username' => 'laboran',
            'email' => 'laboran@ub.ac.id',
            'password' => Hash::make('password123'),
                'nama_lengkap' => 'Laboran Admin',
                'nip' => '1987654321',
                'no_hp' => '081234567899',
                'alamat' => 'Jl. Laboran No. 1'
            ]
        ];

        foreach ($laborans as $laboranData) {
            // Create user
            $user = User::firstOrCreate(
                ['username' => $laboranData['username']],
                [
                    'email' => $laboranData['email'],
                    'password' => $laboranData['password'],
                    'role_id' => 1
                ]
            );

            // Create profil
            ProfilPengguna::firstOrCreate(
                ['user_id' => $user->user_id],
                [
                    'no_identitas' => $laboranData['nip'],
                    'nama_lengkap' => $laboranData['nama_lengkap'],
                    'program_studi' => null,
                    'no_whatsapp' => $laboranData['no_hp']
                ]
            );

            // Create laboran
            Laboran::firstOrCreate(
                ['user_id' => $user->user_id],
                [
                    'nama' => $laboranData['nama_lengkap'],
                    'nip' => $laboranData['nip'],
                    'no_hp' => $laboranData['no_hp'],
                    'alamat' => $laboranData['alamat']
                ]
            );
        }

        $this->command->info('Laboran account created successfully!');
        $this->command->info('Username: laboran');
        $this->command->info('Password: password123');
    }
}
