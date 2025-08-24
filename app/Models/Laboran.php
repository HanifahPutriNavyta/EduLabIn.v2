<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laboran extends Model
{
    protected $table = 'laborans';
    protected $primaryKey = 'laboran_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'no_hp',
        'alamat'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
} 