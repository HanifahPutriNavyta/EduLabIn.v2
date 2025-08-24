<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KelasPraktikum extends Model
{
    protected $table = 'kelas_praktikums';
    protected $primaryKey = 'kelas_id';
    public $timestamps = true;

    protected $guarded = [];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mk_id', 'mk_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'dosen_id');
    }

    public function aspraks(): HasMany
    {
        return $this->hasMany(Asprak::class, 'kelas_id', 'kelas_id');
    }

    public function beritaAcaras(): HasMany
    {
        return $this->hasMany(BeritaAcara::class, 'kelas_id', 'kelas_id');
    }

    public function absensiPraktikans(): HasMany
    {
        return $this->hasMany(AbsensiPraktikan::class, 'kelas_id', 'kelas_id');
    }

    public function nilaiPraktikums(): HasMany
    {
        return $this->hasMany(NilaiPraktikum::class, 'kelas_id', 'kelas_id');
    }

    public function asprak(): BelongsTo
    {
        return $this->belongsTo(Asprak::class, 'kelas_id', 'kelas_id');
    }
} 