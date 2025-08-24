<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckRoles extends Command
{
    protected $signature = 'roles:check';
    protected $description = 'Check all roles in the database';

    public function handle()
    {
        $roles = DB::table('roles')->get();
        
        if ($roles->isEmpty()) {
            $this->error('No roles found in the database.');
            return;
        }

        $this->info('Roles in database:');
        foreach ($roles as $role) {
            $this->line("- {$role->role_name}");
        }
    }
} 