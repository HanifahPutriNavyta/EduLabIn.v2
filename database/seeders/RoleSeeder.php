<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['role_name' => 'laboran', 'role_id' => 1],
            ['role_name' => 'asprak', 'role_id' => 2],
            ['role_name' => 'dosen', 'role_id' => 3],
            ['role_name' => 'casprak', 'role_id' => 4]
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['role_name' => $role['role_name']],
               
            );
        }

        $this->command->info('Roles seeded successfully!');
    }
} 