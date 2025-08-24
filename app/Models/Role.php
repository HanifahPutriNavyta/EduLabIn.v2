<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    public $timestamps = true;

    protected $fillable = [
        'role_name',
        'description'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }

    public function usersMany(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id')
            ->withTimestamps();
    }
} 