<?php

namespace App\Models;

use App\Enums\Jabatan;
use App\Enums\PeranPengguna;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model akun login sistem KKN.
 * Terhubung ke data anggota via anggota_id untuk absensi & logbook.
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

    /** Izin kelola konten website (proker, kegiatan, galeri) — khusus admin. */
    public function canManageWebsiteKonten(): bool
    {
        return $this->isAdmin();
    }

    /** Izin kelola arsip surat — admin atau Sekretaris. */
    public function canKelolaSurat(): bool
    {
        return $this->isAdmin() || ($this->anggota && Jabatan::tryFromValue($this->anggota->jabatan)?->dapatKelolaCms());
    }

    /** Izin kelola data anggota — khusus admin. */
    public function canManageAnggota(): bool
    {
        return $this->isAdmin();
    }

    /** @deprecated Gunakan canKelolaSurat(). */
    public function canKelolaAdministrasi(): bool
    {
        return $this->canKelolaSurat();
    }

    /** @deprecated Gunakan canKelolaSurat(), canManageAnggota(), atau canManageWebsiteKonten(). */
    public function canManageCms(): bool
    {
        return $this->canKelolaSurat() || $this->canManageWebsiteKonten() || $this->canManageAnggota();
    }

    /** Izin pengaturan sistem — khusus admin. */
    public function canManagePengaturan(): bool
    {
        return $this->isAdmin();
    }

    /** Izin review logbook: admin atau koordinator. */
    public function canReviewLogbook(): bool
    {
        return $this->isAdmin() || $this->isKoordinator();
    }

    /** Izin check-in absensi QR: anggota/koordinator yang terhubung ke data anggota. */
    public function canCheckInAbsensi(): bool
    {
        return $this->anggota_id !== null && ($this->isAnggota() || $this->isKoordinator());
    }

    /** Izin kelola keuangan: admin, koordinator, atau jabatan Bendahara. */
    public function canManageKeuangan(): bool
    {
        return $this->isAdmin() || $this->isKoordinator() || ($this->anggota && Jabatan::tryFromValue($this->anggota->jabatan)?->dapatKelolaKeuangan());
    }
}
