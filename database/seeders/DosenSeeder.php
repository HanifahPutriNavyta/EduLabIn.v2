<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Dosen;
use App\Models\ProfilPengguna;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            [
                'username' => 'dosen',
                'email' => 'dosen@edulab.com',
            ],
            [
                'password' => Hash::make('password123'),
                'role_id' => 3
            ]
        );

        Dosen::updateOrCreate(
            ['user_id' => $user->user_id]
        );

        $this->command->info('Dosen account created successfully!');
        $this->command->info('Username: dosen');
        $this->command->info('Password: password123');
    }
} 