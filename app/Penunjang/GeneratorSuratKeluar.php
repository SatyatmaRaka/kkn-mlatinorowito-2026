<?php

namespace App\Penunjang;

use App\Layanan\LayananPengaturan;
use App\Models\Surat;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

/**
 * Generate PDF surat keluar dari template sistem (bukan upload manual).
 */
class GeneratorSuratKeluar
{
    /** Buat nomor surat otomatis jika kosong. */
    public static function nomorOtomatis(Surat $surat): string
    {
        $urutan = Surat::query()
            ->where('jenis', 'keluar')
            ->where('id', '<=', $surat->id)
            ->count();

        $bulanRomawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $bulan = $bulanRomawi[(int) $surat->tanggal->format('n') - 1];

        return sprintf('%03d/KKN-MLATI/%s/%s', $urutan, $bulan, $surat->tanggal->format('Y'));
    }

    /** Render & simpan PDF ke storage publik. Mengembalikan path relatif. */
    public static function generate(Surat $surat, User $user): string
    {
        if ($surat->lampiran && str_starts_with($surat->lampiran, 'surat/generated/')) {
            Storage::disk('public')->delete($surat->lampiran);
        }

        $pengaturan = LayananPengaturan::get();

        $pdf = Pdf::loadView('panel.surat.pdf', [
            'surat' => $surat,
            'pengaturan' => $pengaturan,
            'penandatangan' => PenandatanganSurat::data($user),
            'logoKkn' => PenandatanganSurat::logoKkn(),
            'logoUmk' => PenandatanganSurat::logoUmk(),
            'paragrafIsi' => PenandatanganSurat::paragrafIsi($surat->keterangan),
        ])->setPaper('A4');

        $path = 'surat/generated/surat-keluar-'.$surat->id.'.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    public static function namaFileUnduh(Surat $surat): string
    {
        $slug = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $surat->nomor_surat ?? 'surat-keluar-'.$surat->id);

        return trim($slug, '-').'.pdf';
    }
}
