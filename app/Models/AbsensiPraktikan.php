<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiPraktikan extends Model
{
    protected $table = 'absensi_praktikans';
    protected $primaryKey = 'absensi_id';
    public $timestamps = true;

    protected $fillable = [
        'kelas_id',
        'tanggal',
        'deskripsi',
        'upload_file',
        'judul',
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_id', 'kelas_id');
    }
} 