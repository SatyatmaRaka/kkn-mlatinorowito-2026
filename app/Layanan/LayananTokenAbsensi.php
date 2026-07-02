<?php

namespace App\Layanan;

use App\Models\AbsensiToken;
use Illuminate\Support\Str;

/**
 * Layanan token QR absensi harian.
 * Token berubah setiap hari agar QR kemarin tidak bisa dipakai ulang.
 */
class LayananTokenAbsensi
{
    /** Ambil atau buat token untuk hari ini. */
    public static function getOrCreateForToday(): AbsensiToken
    {
        $today = today()->toDateString();

        return AbsensiToken::firstOrCreate(
            ['tanggal' => $today],
            ['token' => Str::random(32)]
        );
    }

    /** Cek apakah token masih valid untuk hari ini. */
    public static function isValid(?string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        return AbsensiToken::whereDate('tanggal', today())
            ->where('token', $token)
            ->exists();
    }

    /** URL halaman check-in absensi beserta token. */
    public static function checkInUrl(?AbsensiToken $tokenModel = null): string
    {
        $tokenModel ??= self::getOrCreateForToday();

        return route('absensi.check-in', ['token' => $tokenModel->token]);
    }
}
