<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilPengguna extends Model
{
    protected $table = 'profil_penggunas';
    protected $primaryKey = 'profile_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'no_identitas',
        'nama_lengkap',
        'fakultas',
        'departemen',
        'program_studi',
        'status_akademik',
        'no_whatsapp',
        'foto_path'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
} 