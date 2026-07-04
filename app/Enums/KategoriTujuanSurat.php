<?php

namespace App\Enums;

/** Kategori penerima surat keluar KKN. */
enum KategoriTujuanSurat: string
{
    case Kelurahan = 'kelurahan';
    case Rt = 'rt';
    case Rw = 'rw';
    case Instansi = 'instansi';

    public function label(): string
    {
        return match ($this) {
            self::Kelurahan => 'Kelurahan',
            self::Rt => 'RT',
            self::Rw => 'RW',
            self::Instansi => 'Instansi / Lainnya',
        };
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
