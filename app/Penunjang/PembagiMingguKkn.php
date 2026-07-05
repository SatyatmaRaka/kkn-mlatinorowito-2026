<?php

namespace App\Penunjang;

use App\Layanan\LayananPengaturan;
use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * Membagi periode KKN menjadi minggu-minggu untuk daftar hadir (Pedoman KKN Lampiran 4).
 */
class PembagiMingguKkn
{
    /**
     * @return list<array{nomor: int, mulai: CarbonInterface, selesai: CarbonInterface, tanggal: list<CarbonInterface>}>
     */
    public static function semuaMinggu(?string $tanggalMulai = null, ?string $tanggalSelesai = null): array
    {
        [$mulai, $selesai] = self::rentang($tanggalMulai, $tanggalSelesai);

        if (! $mulai || ! $selesai || $mulai->gt($selesai)) {
            return [];
        }

        $minggu = [];
        $current = $mulai->copy();
        $nomor = 1;

        while ($current->lte($selesai) && $nomor <= 5) {
            $tanggal = [];

            if ($nomor <= 4) {
                for ($i = 0; $i < 7 && $current->lte($selesai); $i++) {
                    $tanggal[] = $current->copy();
                    $current->addDay();
                }
            } else {
                while ($current->lte($selesai)) {
                    $tanggal[] = $current->copy();
                    $current->addDay();
                }
            }

            if ($tanggal === []) {
                break;
            }

            $minggu[] = [
                'nomor' => $nomor,
                'mulai' => $tanggal[0]->copy(),
                'selesai' => $tanggal[array_key_last($tanggal)]->copy(),
                'tanggal' => $tanggal,
            ];

            $nomor++;
        }

        return $minggu;
    }

    public static function jumlahMinggu(?string $tanggalMulai = null, ?string $tanggalSelesai = null): int
    {
        return count(self::semuaMinggu($tanggalMulai, $tanggalSelesai));
    }

    /**
     * @return array{nomor: int, mulai: CarbonInterface, selesai: CarbonInterface, tanggal: list<CarbonInterface>}|null
     */
    public static function mingguByNomor(int $nomor, ?string $tanggalMulai = null, ?string $tanggalSelesai = null): ?array
    {
        foreach (self::semuaMinggu($tanggalMulai, $tanggalSelesai) as $minggu) {
            if ($minggu['nomor'] === $nomor) {
                return $minggu;
            }
        }

        return null;
    }

    /** @return array{0: ?CarbonInterface, 1: ?CarbonInterface} */
    private static function rentang(?string $tanggalMulai, ?string $tanggalSelesai): array
    {
        if ($tanggalMulai === null || $tanggalSelesai === null) {
            $pengaturan = LayananPengaturan::get();
            $tanggalMulai ??= $pengaturan->get('tanggal_mulai_kkn');
            $tanggalSelesai ??= $pengaturan->get('tanggal_selesai_kkn');
        }

        if (! $tanggalMulai || ! $tanggalSelesai) {
            return [null, null];
        }

        return [
            Carbon::parse($tanggalMulai)->startOfDay(),
            Carbon::parse($tanggalSelesai)->startOfDay(),
        ];
    }
}
