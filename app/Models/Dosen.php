<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    protected $table = 'dosens';
    protected $primaryKey = 'dosen_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function kelasPraktikums(): HasMany
    {
        return $this->hasMany(KelasPraktikum::class, 'dosen_id', 'dosen_id');
    }
} 