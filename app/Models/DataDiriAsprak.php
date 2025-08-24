<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataDiriAsprak extends Model
{
    protected $table = 'data_diri_aspraks';
    protected $primaryKey = 'dataDiri_id';
    public $timestamps = true;

    protected $fillable = [
        'asprak_id',
        'kelas_id',
        'nama',
        'nim',
        'nomor_ktp',
        'nomor_whatsapp',
        'nomor_rekening',
        'jumlah_mahasiswa'
    ];

    public function asprak(): BelongsTo
    {
        return $this->belongsTo(Asprak::class, 'asprak_id', 'asprak_id');
    }

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_id', 'kelas_id');
    }
} 