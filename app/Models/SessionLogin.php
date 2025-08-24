<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionLogin extends Model
{
    protected $table = 'session_logins';
    protected $primaryKey = 'session_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'login_time',
        'logout_time',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'login_time' => 'datetime',
        'logout_time' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
} 