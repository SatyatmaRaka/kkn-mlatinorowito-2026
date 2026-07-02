<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'anggota_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    public function logbooks(): HasMany
    {
        return $this->hasMany(Logbook::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isKoordinator(): bool
    {
        return $this->role === UserRole::Koordinator;
    }

    public function isAnggota(): bool
    {
        return $this->role === UserRole::Anggota;
    }

    public function canManageCms(): bool
    {
        return $this->isAdmin() || ($this->anggota && $this->anggota->jabatan === 'Sekretaris');
    }

    public function canReviewLogbook(): bool
    {
        return $this->isAdmin() || $this->isKoordinator();
    }

    public function canCheckInAbsensi(): bool
    {
        return $this->anggota_id !== null && ($this->isAnggota() || $this->isKoordinator());
    }

    public function canManageKeuangan(): bool
    {
        return $this->isAdmin() || $this->isKoordinator() || ($this->anggota && $this->anggota->jabatan === 'Bendahara');
    }
}
