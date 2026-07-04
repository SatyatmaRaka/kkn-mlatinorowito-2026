<?php

namespace Tests\Unit;

use App\Enums\KategoriTujuanSurat;
use App\Penunjang\PenerimaSurat;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PenerimaSuratTest extends TestCase
{
    #[DataProvider('teksPenerimaProvider')]
    public function test_teks_penerima(
        KategoriTujuanSurat $kategori,
        ?string $nomorRt,
        ?string $nomorRw,
        ?string $manual,
        string $expected,
    ): void {
        $this->assertSame(
            $expected,
            PenerimaSurat::teksPenerima($kategori, $nomorRt, $nomorRw, $manual),
        );
    }

    /** @return array<string, array{0: KategoriTujuanSurat, 1: ?string, 2: ?string, 3: ?string, 4: string}> */
    public static function teksPenerimaProvider(): array
    {
        return [
            'kelurahan' => [KategoriTujuanSurat::Kelurahan, null, null, null, 'Lurah Kelurahan Mlatinorowito'],
            'rt saja' => [KategoriTujuanSurat::Rt, '03', null, null, 'Ketua RT 03 Kelurahan Mlatinorowito'],
            'rt rw' => [KategoriTujuanSurat::Rt, '03', '05', null, 'Ketua RT 03 RW 05 Kelurahan Mlatinorowito'],
            'rw' => [KategoriTujuanSurat::Rw, null, '05', null, 'Ketua RW 05 Kelurahan Mlatinorowito'],
            'instansi' => [KategoriTujuanSurat::Instansi, null, null, 'Dinas Pendidikan Kudus', 'Dinas Pendidikan Kudus'],
        ];
    }
}
