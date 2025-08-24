<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean'
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function sessionLogins(): HasMany
    {
        return $this->hasMany(SessionLogin::class, 'user_id', 'user_id');
    }

    public function profil(): HasOne
    {
        return $this->hasOne(ProfilPengguna::class, 'user_id', 'user_id');
    }

    public function dosen(): HasOne
    {
        return $this->hasOne(Dosen::class, 'user_id', 'user_id');
    }

    public function laboran(): HasOne
    {
        return $this->hasOne(Laboran::class, 'user_id', 'user_id');
    }

    public function calonAsprak(): HasMany
    {
        return $this->hasMany(CalonAsprak::class, 'user_id', 'user_id');
    }

    public function asprak(): HasMany
    {
        return $this->hasMany(Asprak::class, 'user_id', 'user_id');
    }

    public function pengumumans(): HasMany
    {
        return $this->hasMany(Pengumuman::class, 'created_by', 'user_id');
    }

    public function pendaftaranCalonAsprak(): HasMany
    {
        return $this->hasMany(PendaftaranCalonAsprak::class, 'user_id', 'user_id');
    }

    public function beritaAcara(): HasMany
    {
        return $this->hasMany(BeritaAcara::class, 'asprak_id', 'user_id');
    }

    public function hasRole($roleName)
    {
        // Check if user has a role relationship and if the role_name matches
        return $this->role && $this->role->role_name === $roleName;
    }
}
