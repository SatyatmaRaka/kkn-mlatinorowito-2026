<?php

namespace App\Services;

use App\Models\Pengaturan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PengaturanService
{
    public const KEYS = [
        'nama_dpl',
        'nama_kelompok',
        'tagline',
        'email',
        'instagram',
        'periode_kkn',
    ];

    private const CACHE_KEY = 'pengaturan.public';

    private const CACHE_TTL_SECONDS = 300;

    public static function get(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, function () {
            return Pengaturan::whereIn('key', self::KEYS)->pluck('value', 'key');
        });
    }

    public static function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
