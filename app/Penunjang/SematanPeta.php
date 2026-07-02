<?php

namespace App\Penunjang;

/**
 * Validasi & sanitasi URL embed Google Maps di halaman kontak.
 * Mencegah iframe berbahaya dari domain selain Google Maps.
 */
class SematanPeta
{
    private const DEFAULT_URL = 'https://maps.google.com/maps?q=Kelurahan%20Mlatinorowito,%20Kudus&t=&z=15&ie=UTF8&iwloc=&output=embed';

    /** @var list<string> */
    private const ALLOWED_HOSTS = [
        'maps.google.com',
        'www.google.com',
        'google.com',
        'maps.googleapis.com',
    ];

    public static function defaultUrl(): string
    {
        return self::DEFAULT_URL;
    }

    public static function isValidEmbedUrl(string $url): bool
    {
        $parsed = parse_url($url);

        if (! isset($parsed['scheme'], $parsed['host'])) {
            return false;
        }

        if (! in_array(strtolower($parsed['scheme']), ['http', 'https'], true)) {
            return false;
        }

        return in_array(strtolower($parsed['host']), self::ALLOWED_HOSTS, true);
    }

    public static function isValidEmbedInput(?string $value): bool
    {
        if ($value === null || $value === '') {
            return true;
        }

        return self::isValidEmbedUrl($value);
    }

    /** Kembalikan URL aman; fallback ke peta default jika tidak valid. */
    public static function safeUrl(?string $url): string
    {
        if ($url === null || $url === '' || ! self::isValidEmbedUrl($url)) {
            return self::DEFAULT_URL;
        }

        return $url;
    }
}
