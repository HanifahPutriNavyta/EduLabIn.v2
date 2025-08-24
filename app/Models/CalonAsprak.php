<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CalonAsprak extends Model
{
    protected $table = 'calon_aspraks';
    protected $primaryKey = 'calon_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'pendaftaran_id',
        'mk_id',
        'tanggal_daftar',
        'status_seleksi'
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
        'status_seleksi' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function pendaftaranAsprak(): BelongsTo
    {
        return $this->belongsTo(PendaftaranAsprak::class, 'pendaftaran_id', 'pendaftaran_id');
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mk_id', 'mk_id');
    }

    public function dataCalonAsprak(): HasOne
    {
        return $this->hasOne(DataCalonAsprak::class, 'calon_id', 'calon_id');
    }
} 