<?php

namespace App\Support;

class SocialLinks
{
    public static function instagramUrl(?string $handle, string $defaultHandle = 'kknumk.mlatinorowito.26'): string
    {
        if (empty($handle)) {
            return 'https://www.instagram.com/' . $defaultHandle;
        }

        if (str_starts_with($handle, 'http')) {
            return $handle;
        }

        return 'https://www.instagram.com/' . ltrim($handle, '@');
    }

    public static function instagramLabel(?string $handle, string $default = '@kknumk.mlatinorowito.26'): string
    {
        $value = $handle ?: $default;

        return str_starts_with($value, '@') ? $value : '@' . ltrim($value, '@');
    }

    /**
     * @return list<string>
     */
    public static function jabatanPimpinan(): array
    {
        return ['Koordinator Desa', 'Wakil Koordinator'];
    }

    public static function isJabatanPimpinan(?string $jabatan): bool
    {
        return in_array($jabatan, self::jabatanPimpinan(), true);
    }
}
