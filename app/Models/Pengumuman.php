<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';
    protected $primaryKey = 'pengumuman_id';
    public $timestamps = true;

    protected $fillable = [
        'created_by',
        'judul',
        'deskripsi',
        'tanggal_posting',
        'tanggal_berakhir',
        'gambar',
        'status'
    ];

    protected $casts = [
        'tanggal_posting' => 'date',
        'tanggal_berakhir' => 'date',
        'status' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }
} 