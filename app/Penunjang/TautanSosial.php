<?php

namespace App\Penunjang;

use App\Enums\Jabatan;

/**
 * Pembantu tautan media sosial & jabatan pimpinan kelompok.
 */
class TautanSosial
{
    /** Bangun URL Instagram dari handle atau URL penuh. */
    public static function instagramUrl(?string $handle, string $defaultHandle = 'kknumk.mlatinorowito.26'): string
    {
        if (empty($handle)) {
            return 'https://www.instagram.com/'.$defaultHandle;
        }

        if (str_starts_with($handle, 'http')) {
            return self::isValidInstagramUrl($handle)
                ? $handle
                : 'https://www.instagram.com/'.$defaultHandle;
        }

        return 'https://www.instagram.com/'.ltrim($handle, '@');
    }

    public static function instagramLabel(?string $handle, string $default = '@kknumk.mlatinorowito.26'): string
    {
        $value = $handle ?: $default;

        return str_starts_with($value, '@') ? $value : '@'.ltrim($value, '@');
    }

    public static function isValidInstagramUrl(string $url): bool
    {
        $parsed = parse_url($url);

        if (! isset($parsed['host'], $parsed['scheme'])) {
            return false;
        }

        if (! in_array(strtolower($parsed['scheme']), ['http', 'https'], true)) {
            return false;
        }

        return in_array(strtolower($parsed['host']), ['www.instagram.com', 'instagram.com'], true);
    }

    public static function isValidInstagramInput(?string $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }

        if (str_starts_with($value, 'http')) {
            return self::isValidInstagramUrl($value);
        }

        return (bool) preg_match('/^@?[\w.]+$/', $value);
    }

    /** @return list<string> */
    public static function jabatanPimpinan(): array
    {
        return Jabatan::pimpinanValues();
    }

    public static function isJabatanPimpinan(?string $jabatan): bool
    {
        return Jabatan::tryFromValue($jabatan)?->isPimpinan() ?? false;
    }
}
