<?php

namespace App\Penunjang;

use App\Layanan\LayananPengaturan;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Support\Collection;

/** Data penandatangan surat resmi KKN. */
class PenandatanganSurat
{
    /** @return array{koordinator: array{nama: string, nim: ?string, jabatan: string}, sekretaris: array{nama: string, nim: ?string, jabatan: string}, dpl: array{nama: string, jabatan: string}} */
    public static function data(?User $penandatangan = null): array
    {
        $pengaturan = LayananPengaturan::get();

        $koordinator = Anggota::query()
            ->where('jabatan', 'Koordinator Desa')
            ->orderBy('urutan')
            ->first();

        $sekretaris = self::sekretarisPenandatangan($penandatangan);

        return [
            'koordinator' => [
                'nama' => $koordinator?->nama ?? 'Koordinator Desa',
                'nim' => $koordinator?->nim,
                'jabatan' => 'Koordinator Desa',
            ],
            'sekretaris' => [
                'nama' => $sekretaris?->nama ?? ($penandatangan?->name ?? 'Sekretaris'),
                'nim' => $sekretaris?->nim,
                'jabatan' => 'Sekretaris',
            ],
            'dpl' => [
                'nama' => $pengaturan->get('nama_dpl', 'Dosen Pembimbing Lapangan'),
                'jabatan' => 'Dosen Pembimbing Lapangan',
            ],
        ];
    }

    public static function logoKknDataUri(): ?string
    {
        return self::logoDataUri('logo.png');
    }

    public static function logoUmkDataUri(): ?string
    {
        return self::logoDataUri('logo-umk.png');
    }

    /** @return array{src: string, width: int, height: int}|null */
    public static function logoKkn(int $maxHeight = 64, bool $dataUri = true): ?array
    {
        return self::logo('logo.png', $maxHeight, $dataUri);
    }

    /** @return array{src: string, width: int, height: int}|null */
    public static function logoUmk(int $maxHeight = 64, bool $dataUri = true): ?array
    {
        return self::logo('logo-umk.png', $maxHeight, $dataUri);
    }

    /** @return array{src: string, width: int, height: int}|null */
    public static function logo(string $filename, int $maxHeight = 64, bool $dataUri = true): ?array
    {
        $path = public_path('images/'.$filename);

        if (! is_file($path)) {
            return null;
        }

        $size = @getimagesize($path);
        if ($size === false) {
            return null;
        }

        [$origW, $origH] = $size;
        $height = $maxHeight;
        $width = max(1, (int) round($origW * ($maxHeight / $origH)));

        return [
            'src' => $dataUri ? self::logoDataUri($filename) : asset('images/'.$filename),
            'width' => $width,
            'height' => $height,
        ];
    }

    public static function logoDataUri(string $filename): ?string
    {
        $path = public_path('images/'.$filename);

        if (! is_file($path)) {
            return null;
        }

        $mime = match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            default => 'image/png',
        };

        return 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($path));
    }

    /** @return Collection<int, string> */
    public static function paragrafIsi(?string $keterangan): Collection
    {
        $teks = trim((string) $keterangan);

        if ($teks === '') {
            return collect([
                'Melalui surat ini kami sampaikan hal sebagaimana tertera pada perihal di atas terkait kegiatan Kuliah Kerja Nyata (KKN) di Kelurahan Mlatinorowito, Kudus.',
            ]);
        }

        $bagian = preg_split("/\R\s*\R/u", $teks) ?: [$teks];

        return collect($bagian)
            ->map(fn (string $p) => trim($p))
            ->filter()
            ->values();
    }

    private static function sekretarisPenandatangan(?User $penandatangan): ?Anggota
    {
        if ($penandatangan?->anggota?->jabatan === 'Sekretaris') {
            return $penandatangan->anggota;
        }

        return Anggota::query()
            ->where('jabatan', 'Sekretaris')
            ->orderBy('urutan')
            ->first();
    }
}
