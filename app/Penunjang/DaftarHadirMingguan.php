<?php

namespace App\Penunjang;

use App\Enums\Jabatan;
use App\Models\Absensi;
use App\Models\Anggota;
use App\Layanan\LayananPengaturan;
use Carbon\Carbon;
use Carbon\CarbonInterface;

/**
 * Menyusun data daftar hadir mingguan dari absensi sistem.
 */
class DaftarHadirMingguan
{
    /**
     * @param  array{nomor: int, mulai: CarbonInterface, selesai: CarbonInterface, tanggal: list<CarbonInterface>}  $minggu
     * @return array<string, mixed>
     */
    public static function susun(array $minggu): array
    {
        $pengaturan = LayananPengaturan::get();
        $tanggalStrings = collect($minggu['tanggal'])->map(fn (CarbonInterface $d) => $d->toDateString());

        $anggotaList = Anggota::query()
            ->with('user')
            ->whereHas('user')
            ->orderBy('urutan')
            ->get();

        $absensi = Absensi::query()
            ->whereIn('anggota_id', $anggotaList->pluck('id'))
            ->whereDate('tanggal', '>=', $minggu['mulai']->toDateString())
            ->whereDate('tanggal', '<=', $minggu['selesai']->toDateString())
            ->get()
            ->groupBy(fn (Absensi $a) => $a->anggota_id.'|'.$a->tanggal->toDateString());

        $baris = $anggotaList->values()->map(function (Anggota $anggota, int $index) use ($tanggalStrings, $absensi) {
            $simbol = [];
            $keteranganParts = [];

            foreach ($tanggalStrings as $tanggal) {
                $record = $absensi->get($anggota->id.'|'.$tanggal)?->first();
                $simbol[$tanggal] = self::simbolUntuk($record);

                if ($record && in_array($record->status, [Absensi::STATUS_IZIN, Absensi::STATUS_SAKIT], true) && $record->keterangan) {
                    $keteranganParts[] = Carbon::parse($tanggal)->format('d/m').': '.$record->keterangan;
                }
            }

            return [
                'no' => $index + 1,
                'nim' => $anggota->nim ?? '-',
                'nama' => $anggota->nama,
                'simbol' => $simbol,
                'keterangan' => implode('; ', $keteranganParts),
            ];
        });

        $kordes = Anggota::query()
            ->where('jabatan', Jabatan::KoordinatorDesa->value)
            ->first();

        return [
            'minggu' => $minggu,
            'tanggal' => $minggu['tanggal'],
            'baris' => $baris,
            'desa' => $pengaturan->get('desa', ''),
            'kecamatan' => $pengaturan->get('kecamatan', ''),
            'kabupaten' => $pengaturan->get('kabupaten', ''),
            'nama_dpl' => $pengaturan->get('nama_dpl', ''),
            'nidn_dpl' => $pengaturan->get('nidn_dpl', ''),
            'kordes_nama' => $kordes?->nama ?? '-',
            'kordes_nim' => $kordes?->nim ?? '-',
        ];
    }

    private static function simbolUntuk(?Absensi $record): string
    {
        if (! $record) {
            return '-';
        }

        return match ($record->status) {
            Absensi::STATUS_HADIR => 'H',
            Absensi::STATUS_IZIN => 'I',
            Absensi::STATUS_SAKIT => 'S',
            default => '-',
        };
    }
}
