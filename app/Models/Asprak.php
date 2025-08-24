<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asprak extends Model
{
    protected $table = 'aspraks';
    protected $primaryKey = 'asprak_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'kelas_id',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function kelasPraktikum(): BelongsTo
    {
        return $this->belongsTo(KelasPraktikum::class, 'kelas_id', 'kelas_id');
    }

    public function dataDiriAsprak(): HasOne
    {
        return $this->hasOne(DataDiriAsprak::class, 'asprak_id', 'asprak_id');
    }

    public function beritaAcaras(): HasMany
    {
        return $this->hasMany(BeritaAcara::class, 'asprak_id', 'asprak_id');
    }

    public function nilaiPraktikums(): HasMany
    {
        return $this->hasMany(NilaiPraktikum::class, 'asprak_id', 'asprak_id');
    }
} 