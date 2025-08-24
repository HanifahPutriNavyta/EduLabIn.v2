<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaAcara extends Model
{
    protected $table = 'berita_acaras';
    protected $primaryKey = 'berita_id';
    public $timestamps = true;

    protected $fillable = [
        'kelas_id',
        'asprak_id',
        'judul',
        'deskripsi_kegiatan',
        'tipe_pertemuan',
        'foto_pertemuan',
        'upload_berita_acara',
        'upload_bukti_pertemuan',
        'tanggal_kegiatan'
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date'
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