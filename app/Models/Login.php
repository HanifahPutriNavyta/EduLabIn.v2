<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $table = 'logins';
    protected $primaryKey = 'login_id';
    public $timestamps = true;

    protected $fillable = [
        'username',
        'password',
        'role'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'role' => 'boolean'
    ];
} 