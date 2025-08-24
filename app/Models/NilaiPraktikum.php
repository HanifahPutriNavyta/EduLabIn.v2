<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiPraktikum extends Model
{
    protected $table = 'nilai_praktikums';
    protected $primaryKey = 'nilai_id';
    public $timestamps = true;

    protected $fillable = [
        'kelas_id',
        'asprak_id',
        'judul',
        'deskripsi',
        'tanggal',
        'upload_file'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_id', 'kelas_id');
    }

    public function asprak(): BelongsTo
    {
        return $this->belongsTo(Asprak::class, 'asprak_id', 'asprak_id');
    }
} 