<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PendaftaranAsprak extends Model
{
    protected $table = 'pendaftaran_aspraks';
    protected $primaryKey = 'pendaftaran_id';
    public $timestamps = true;

    protected $fillable = [
        'mk_id',
        'tanggal_buka',
        'tanggal_tutup',
        'kuota',
        'ketentuan',
    'status_pendaftaran'
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
    'tanggal_tutup' => 'date',
    'status_pendaftaran' => 'boolean'
    ];

    public function matakuliah(): BelongsTo
    {
        return $this->belongsTo(Matakuliah::class, 'mk_id', 'mk_id');
    }

    public function calonAspraks(): HasMany
    {
        return $this->hasMany(CalonAsprak::class, 'pendaftaran_id', 'pendaftaran_id');
    }
} 