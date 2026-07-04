<?php

namespace App\Penunjang;

use App\Enums\KategoriTujuanSurat;
use App\Models\Surat;

/** Susun baris penerima surat keluar berdasarkan kategori tujuan. */
class PenerimaSurat
{
    public const NAMA_KELURAHAN = 'Kelurahan Mlatinorowito';

    public static function teksPenerima(
        KategoriTujuanSurat|string|null $kategori,
        ?string $nomorRt = null,
        ?string $nomorRw = null,
        ?string $asalTujuanManual = null,
    ): string {
        $kategori = self::parseKategori($kategori);

        if ($kategori === null) {
            return trim((string) $asalTujuanManual) ?: self::NAMA_KELURAHAN;
        }

        return match ($kategori) {
            KategoriTujuanSurat::Kelurahan => 'Lurah '.self::NAMA_KELURAHAN,
            KategoriTujuanSurat::Rt => self::teksRt($nomorRt, $nomorRw),
            KategoriTujuanSurat::Rw => self::teksRw($nomorRw),
            KategoriTujuanSurat::Instansi => trim((string) $asalTujuanManual),
        };
    }

    public static function fromSurat(Surat $surat): string
    {
        if ($surat->kategori_tujuan) {
            return self::teksPenerima(
                $surat->kategori_tujuan,
                $surat->nomor_rt,
                $surat->nomor_rw,
                $surat->asal_tujuan,
            );
        }

        return $surat->asal_tujuan;
    }

    /** @param  array<string, mixed>  $data */
    public static function lengkapiDataKeluar(array $data): array
    {
        $kategori = KategoriTujuanSurat::from($data['kategori_tujuan']);

        if ($kategori !== KategoriTujuanSurat::Rt) {
            $data['nomor_rt'] = null;
        }

        if (! in_array($kategori, [KategoriTujuanSurat::Rt, KategoriTujuanSurat::Rw], true)) {
            $data['nomor_rw'] = null;
        }

        $data['asal_tujuan'] = self::teksPenerima(
            $kategori,
            $data['nomor_rt'] ?? null,
            $data['nomor_rw'] ?? null,
            $data['asal_tujuan'] ?? null,
        );

        return $data;
    }

    private static function teksRt(?string $nomorRt, ?string $nomorRw): string
    {
        $rt = self::formatNomor($nomorRt);
        $rw = self::formatNomor($nomorRw);

        if ($rw !== null) {
            return "Ketua RT {$rt} RW {$rw} ".self::NAMA_KELURAHAN;
        }

        return "Ketua RT {$rt} ".self::NAMA_KELURAHAN;
    }

    private static function teksRw(?string $nomorRw): string
    {
        return 'Ketua RW '.self::formatNomor($nomorRw).' '.self::NAMA_KELURAHAN;
    }

    private static function formatNomor(?string $nomor): ?string
    {
        $nomor = trim((string) $nomor);

        return $nomor !== '' ? $nomor : null;
    }

    private static function parseKategori(KategoriTujuanSurat|string|null $kategori): ?KategoriTujuanSurat
    {
        if ($kategori instanceof KategoriTujuanSurat) {
            return $kategori;
        }

        if ($kategori === null || $kategori === '') {
            return null;
        }

        return KategoriTujuanSurat::from($kategori);
    }
}
