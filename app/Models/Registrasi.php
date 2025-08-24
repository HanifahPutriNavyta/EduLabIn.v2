<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    protected $table = 'registrasis';
    protected $primaryKey = 'registrasi_id';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'confirm_password'
    ];

    protected $hidden = [
        'password',
        'confirm_password'
    ];
} 