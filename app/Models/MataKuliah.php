<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliahs';
    protected $primaryKey = 'mk_id';
    public $timestamps = true;

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
    'semester'
    ];

    public function kelasPraktikums(): HasMany
    {
        return $this->hasMany(KelasPraktikum::class, 'mk_id', 'mk_id');
    }

    public function calonAspraks(): HasMany
    {
        return $this->hasMany(CalonAsprak::class, 'mk_id', 'mk_id');
    }

    public function pendaftaranAspraks(): BelongsTo
    {
        return $this->belongsTo(
            PendaftaranAsprak::class,
            'mk_id',
            'mk_id'
        );
    }
} 