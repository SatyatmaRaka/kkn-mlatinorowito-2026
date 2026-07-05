<?php

namespace App\Penunjang;

use Carbon\Carbon;

/** Hitung durasi dari jam_mulai / jam_selesai (format H:i atau H:i:s). */
class DurasiKegiatan
{
    public static function format(?string $jamMulai, ?string $jamSelesai): string
    {
        $menit = self::menit($jamMulai, $jamSelesai);

        if ($menit <= 0) {
            return '-';
        }

        $jam = intdiv($menit, 60);
        $sisa = $menit % 60;

        if ($jam > 0 && $sisa > 0) {
            return "{$jam} jam {$sisa} menit";
        }

        if ($jam > 0) {
            return "{$jam} jam";
        }

        return "{$sisa} menit";
    }

    public static function menit(?string $jamMulai, ?string $jamSelesai): int
    {
        if (! $jamMulai || ! $jamSelesai) {
            return 0;
        }

        try {
            $mulai = Carbon::createFromFormat('H:i:s', strlen($jamMulai) === 5 ? $jamMulai.':00' : $jamMulai);
            $selesai = Carbon::createFromFormat('H:i:s', strlen($jamSelesai) === 5 ? $jamSelesai.':00' : $jamSelesai);
        } catch (\Exception) {
            return 0;
        }

        if ($selesai->lte($mulai)) {
            return 0;
        }

        return (int) $mulai->diffInMinutes($selesai);
    }

    public static function jamDesimal(?string $jamMulai, ?string $jamSelesai): float
    {
        return round(self::menit($jamMulai, $jamSelesai) / 60, 2);
    }
}
