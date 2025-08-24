<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserRole extends Command
{
    protected $signature = 'user:check-role {user_id}';
    protected $description = 'Check user role';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::with('role')->find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }

        $this->info("User: {$user->username}");
        $this->info("Role: " . ($user->role ? $user->role->role_name : 'No role assigned'));
    }
} 