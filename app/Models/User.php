<?php

namespace App\Models;

use App\Enums\Jabatan as JabatanOrganisasi;
use App\Enums\PeranPengguna;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model akun login sistem KKN.
 *
 * Izin dihitung dari kombinasi:
 * - role (admin / koordinator / anggota)
 * - jabatan organisasi lewat relasi anggota (Sekretaris, Bendahara, Wakil Koordinator, dll.)
 */
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
        'wajib_ganti_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => PeranPengguna::class,
            'wajib_ganti_password' => 'boolean',
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

    /** Jabatan organisasi dari profil anggota terkait. */
    public function jabatanOrganisasi(): ?JabatanOrganisasi
    {
        return JabatanOrganisasi::tryFromValue($this->anggota?->jabatan);
    }

    public function isAdmin(): bool
    {
        return $this->role === PeranPengguna::Admin;
    }

    public function isKoordinator(): bool
    {
        return $this->role === PeranPengguna::Koordinator;
    }

    public function isAnggota(): bool
    {
        return $this->role === PeranPengguna::Anggota;
    }

    /** Izin kelola konten website (proker, anggota) — khusus admin. */
    public function canManageWebsiteKonten(): bool
    {
        return $this->isAdmin();
    }

    /** Izin kelola data anggota — khusus admin. */
    public function canManageAnggota(): bool
    {
        return $this->isAdmin();
    }

    /** Izin pengaturan sistem — khusus admin. */
    public function canManagePengaturan(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Izin review logbook & pantau absensi.
     * Admin, role koordinator, atau jabatan pimpinan (Koordinator Desa / Wakil Koordinator).
     */
    public function canReviewLogbook(): bool
    {
        if ($this->isAdmin() || $this->isKoordinator()) {
            return true;
        }

        return $this->jabatanOrganisasi()?->isPimpinan() ?? false;
    }

    /** Alias eksplisit untuk middleware pantau operasional. */
    public function canPantauOperasional(): bool
    {
        return $this->canReviewLogbook();
    }

    /** Izin check-in absensi QR: anggota/koordinator yang terhubung ke data anggota. */
    public function canCheckInAbsensi(): bool
    {
        return $this->anggota_id !== null && ($this->isAnggota() || $this->isKoordinator());
    }

    /** Izin kelola keuangan: admin, koordinator, atau jabatan Bendahara. */
    public function canManageKeuangan(): bool
    {
        return $this->isAdmin()
            || $this->isKoordinator()
            || ($this->jabatanOrganisasi()?->dapatKelolaKeuangan() ?? false);
    }
}
