<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProfilPengguna;
use App\Models\Asprak;
use App\Models\KelasPraktikum;
use Illuminate\Support\Facades\Hash;

class AsprakSeeder extends Seeder
{
    public function run(): void
    {
        $aspraks = [
            [
                'username' => 'hani',
                'email' => 'hani@ub.ac.id',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Hani',
                'nim' => '225150123213123',
                'angkatan' => '2025',
                'jurusan' => 'Teknik Informatika',
                'no_hp' => '081234567890',
                'status_akademik' => 'aktif',
                // 'alamat' => 'Jl. Contoh No. 1, Bandung'
            ],
            [
                'username' => 'asprak1',
                'email' => 'asprak1@ub.ac.id',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Budi Santoso',
                'nim' => '2021001',
                'angkatan' => '2021',
                'jurusan' => 'Teknik Informatika',
                'no_hp' => '081234567890',
                'status_akademik' => 'aktif',
                'foto_profil' => null,
                'fakultas'  => 'Fakultas Ilmu Komputer',
                'departemen' => 'Departemen Teknik Informatika',
            ],
            [
                'username' => 'asprak2',
                'email' => 'asprak2@ub.ac.id',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Ani Wijaya',
                'nim' => '2021002',
                'angkatan' => '2021',
                'jurusan' => 'Teknik Informatika',
                'no_hp' => '081234567891',
                'status_akademik' => 'aktif',
                'foto_profil' => 'foto-diri/E2ycYsuj6ClNejPLGslkZd6hYZGgHxh2f4H3voco.jpg',
                'fakultas'  => 'Fakultas Ilmu Komputer',
                'departemen' => 'Departemen Teknik Informatika',
            ],
            [
                'username' => 'asprak3',
                'email' => 'asprak3@ub.ac.id',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Dewi Putri',
                'nim' => '2021003',
                'angkatan' => '2021',
                'jurusan' => 'Teknik Informatika',
                'no_hp' => '081234567892',
                'status_akademik' => 'non-aktif',
                'foto_profil' => null,
                'fakultas'  => 'Fakultas Ilmu Komputer',
                'departemen' => 'Departemen Teknik Informatika',
            ],
            [
                'username' => 'asprak4',
                'email' => 'asprak4@ub.ac.id',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Rudi Hartono',
                'nim' => '2021004',
                'angkatan' => '2021',
                'jurusan' => 'Teknik Informatika',
                'no_hp' => '081234567893',
                'status_akademik' => 'non-aktif',
                'foto_profil' => null,
                'fakultas'  => 'Fakultas Ilmu Komputer',
                'departemen' => 'Departemen Teknik Informatika',
            ],
            [
                'username' => 'asprak5',
                'email' => 'asprak5@ub.ac.id',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Siti Aminah',
                'nim' => '2021005',
                'angkatan' => '2021',
                'jurusan' => 'Teknik Informatika',
                'no_hp' => '081234567894',
                'status_akademik' => 'non-aktif',
                'foto_profil' => null,
                'fakultas'  => 'Fakultas Ilmu Komputer',
                'departemen' => 'Departemen Teknik Informatika',
            ],
            [
                'username' => 'hanifah',
                'email' => 'email@gmail.com',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Hanifah Putri Navyta',
                'nim' => '225150207111101',
                'angkatan' => '2022',
                'jurusan' => 'Teknik Informatika',
                'no_hp' => '1234567891011',
                'status_akademik' => 'aktif',
                'foto_path' => "foto-diri/FoVkzBhzhgwMaeYk2kMxDWopy5opKxuYorhpZ3Ww.png",
                'fakultas'  => 'Fakultas Ilmu Komputer',
                'departemen' => 'Departemen Teknik Informatika',                             
            ]
        ];

        foreach ($aspraks as $asprakData) {
                // Create or update user
                $user = User::updateOrCreate(
                    ['username' => $asprakData['username']],
                    [
                        'email' => $asprakData['email'],
                        'password' => $asprakData['password'],
                        'role_id' => 2
                    ]
                );

                // Create or update profil
                ProfilPengguna::updateOrCreate(
                    ['user_id' => $user->user_id],
                    [
                        'no_identitas' => $asprakData['nim'],
                        'nama_lengkap' => $asprakData['nama_lengkap'],
                        'program_studi' => $asprakData['jurusan'],
                        'no_whatsapp' => $asprakData['no_hp'],
                        'status_akademik' => $asprakData['status_akademik'],
                        'foto_path' => $asprakData['foto_profil'] ?? '',
                        'fakultas' => $asprakData['fakultas'] ?? '',
                        'departemen' => $asprakData['departemen'] ?? '',
                    ]
                );

                // Get kelas_id from KelasPraktikum
                $kelas = KelasPraktikum::first();
                if ($kelas) {
                    Asprak::updateOrCreate(
                        ['user_id' => $user->user_id],
                        [
                            'kelas_id' => $kelas->kelas_id,
                            'status' => true
                        ]
                    );
                }
        }
    }
} 