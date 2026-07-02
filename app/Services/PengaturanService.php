<?php

namespace App\Services;

use App\Models\Pengaturan;
use Carbon\Carbon;
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
        'whatsapp',
        'alamat',
        'maps_embed_url',
        'absensi_jam_mulai',
        'absensi_jam_selesai',
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

    public static function absensiWindowOpen(): bool
    {
        $settings = self::get();
        $start = $settings->get('absensi_jam_mulai', '06:00');
        $end = $settings->get('absensi_jam_selesai', '09:00');

        $now = Carbon::now();
        $today = $now->toDateString();

        return $now->between(
            Carbon::parse("{$today} {$start}"),
            Carbon::parse("{$today} {$end}")
        );
    }

    public static function absensiWindowLabel(): string
    {
        $settings = self::get();
        $start = $settings->get('absensi_jam_mulai', '06:00');
        $end = $settings->get('absensi_jam_selesai', '09:00');

        return "{$start} – {$end} WIB";
    }
}
