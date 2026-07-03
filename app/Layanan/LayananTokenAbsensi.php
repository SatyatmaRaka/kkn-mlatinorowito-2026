<?php

namespace App\Layanan;

use App\Models\AbsensiToken;
use Illuminate\Support\Str;

/**
 * Layanan token QR absensi posko.
 * Satu token tetap untuk seluruh periode KKN — QR tidak perlu diganti setiap hari.
 * Keamanan tetap dijaga: wajib login, jendela jam absensi, satu kali absen per hari.
 */
class LayananTokenAbsensi
{
    /** Ambil token aktif, atau buat sekali jika belum ada. */
    public static function getActive(): AbsensiToken
    {
        $existing = AbsensiToken::query()->orderBy('id')->first();

        if ($existing) {
            return $existing;
        }

        return AbsensiToken::create([
            'tanggal' => today(),
            'token' => Str::random(32),
        ]);
    }

    /** @deprecated Gunakan getActive() — alias agar kode lama tetap jalan. */
    public static function getOrCreateForToday(): AbsensiToken
    {
        return self::getActive();
    }

    /** Buat ulang token (manual, mis. jika QR bocor). */
    public static function regenerate(): AbsensiToken
    {
        $active = self::getActive();
        $active->update(['token' => Str::random(32)]);

        return $active->fresh();
    }

    /** Cek apakah token cocok dengan token aktif posko. */
    public static function isValid(?string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        return hash_equals(self::getActive()->token, $token);
    }

    /** URL halaman check-in absensi beserta token. */
    public static function checkInUrl(?AbsensiToken $tokenModel = null): string
    {
        $tokenModel ??= self::getActive();

        return route('absensi.check-in', ['token' => $tokenModel->token]);
    }
}
