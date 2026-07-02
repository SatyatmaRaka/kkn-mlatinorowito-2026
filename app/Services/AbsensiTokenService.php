<?php

namespace App\Services;

use App\Models\AbsensiToken;
use Illuminate\Support\Str;

class AbsensiTokenService
{
    public static function getOrCreateForToday(): AbsensiToken
    {
        $today = today()->toDateString();

        return AbsensiToken::firstOrCreate(
            ['tanggal' => $today],
            ['token' => Str::random(32)]
        );
    }

    public static function isValid(?string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        return AbsensiToken::whereDate('tanggal', today())
            ->where('token', $token)
            ->exists();
    }

    public static function checkInUrl(?AbsensiToken $tokenModel = null): string
    {
        $tokenModel ??= self::getOrCreateForToday();

        return route('absensi.check-in', ['token' => $tokenModel->token]);
    }
}
