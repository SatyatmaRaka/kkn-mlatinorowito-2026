<?php

namespace App\Enums;

/**
 * Peran akun di sistem KKN.
 *
 * - Admin: kelola penuh panel & pengaturan
 * - Koordinator: review logbook, rekap absensi, keuangan
 * - Anggota: logbook & absensi pribadi
 */
enum PeranPengguna: string
{
    case Admin = 'admin';
    case Koordinator = 'koordinator';
    case Anggota = 'anggota';

    /** Label tampilan di sidebar panel. */
    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Koordinator => 'Koordinator',
            self::Anggota => 'Anggota',
        };
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
