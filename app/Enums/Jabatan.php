<?php

namespace App\Enums;

/**
 * Jabatan organisasi kelompok KKN.
 * Dipakai validasi form anggota & pengecekan izin CMS/keuangan.
 */
enum Jabatan: string
{
    case KoordinatorDesa = 'Koordinator Desa';
    case WakilKoordinator = 'Wakil Koordinator';
    case PDD = 'PDD';
    case Perlengkapan = 'Perlengkapan';
    case Humas = 'Humas';
    case Sekretaris = 'Sekretaris';
    case Bendahara = 'Bendahara';

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /** @return list<string> */
    public static function pimpinanValues(): array
    {
        return [
            self::KoordinatorDesa->value,
            self::WakilKoordinator->value,
        ];
    }

    public function isPimpinan(): bool
    {
        return in_array($this, [self::KoordinatorDesa, self::WakilKoordinator], true);
    }

    /** Sekretaris mengelola CMS website. */
    public function dapatKelolaCms(): bool
    {
        return $this === self::Sekretaris;
    }

    /** Bendahara mengelola keuangan (selain admin/koordinator). */
    public function dapatKelolaKeuangan(): bool
    {
        return $this === self::Bendahara;
    }

    public static function tryFromValue(?string $value): ?self
    {
        return $value === null ? null : self::tryFrom($value);
    }
}
