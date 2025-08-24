<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataCalonAsprak extends Model
{
    protected $table = 'data_calon_aspraks';
    protected $primaryKey = 'calonAsprak_id';
    public $timestamps = true;

    protected $fillable = [
        'calon_id',
        'nama',
        'nim',
        'email',
        'prodi',
        'nomor_whatsapp',
        'tahun_ajaran',
        'pilihan_kelas_praktikum'
    ];

    protected $casts = [
        'pilihan_kelas_praktikum' => 'boolean'
    ];

    public function calonAsprak(): BelongsTo
    {
        return $this->belongsTo(CalonAsprak::class, 'calon_id', 'calon_id');
    }
} 