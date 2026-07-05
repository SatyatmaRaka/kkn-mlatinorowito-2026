<?php

namespace App\Penunjang;

use App\Layanan\LayananPengaturan;
use App\Models\Anggota;
use App\Models\Logbook;
use Illuminate\Support\Collection;

/** Menyusun data laporan logbook harian & rekap keaktifan (Lampiran 8 & 9). */
class LogbookLaporan
{
    /** @return array{dari: string, sampai: string, anggota_id: ?int, baris: Collection, anggotaList: Collection, pengaturan: Collection} */
    public static function dataLogbookHarian(string $dari, string $sampai, ?int $anggotaId = null): array
    {
        $logbooks = Logbook::query()
            ->with('anggota')
            ->where('status', Logbook::STATUS_APPROVED)
            ->whereDate('tanggal', '>=', $dari)
            ->whereDate('tanggal', '<=', $sampai)
            ->when($anggotaId, fn ($q) => $q->where('anggota_id', $anggotaId))
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        $baris = $logbooks->values()->map(function (Logbook $logbook, int $index) {
            return [
                'no' => $index + 1,
                'hari_tanggal' => $logbook->tanggal->locale('id')->translatedFormat('l, d/m/Y'),
                'waktu_durasi' => self::waktuDanDurasi($logbook),
                'kegiatan' => trim($logbook->judul.($logbook->deskripsi ? ': '.$logbook->deskripsi : '')),
                'tempat' => $logbook->lokasi ?? '-',
                'pic' => $logbook->anggota?->nama ?? '-',
                'foto' => $logbook->foto,
            ];
        });

        return [
            'dari' => $dari,
            'sampai' => $sampai,
            'anggota_id' => $anggotaId,
            'baris' => $baris,
            'anggotaList' => Anggota::query()->whereHas('user')->orderBy('urutan')->get(),
            'pengaturan' => LayananPengaturan::get(),
        ];
    }

    /** @return array{baris: Collection, pengaturan: Collection} */
    public static function dataRekapKeaktifan(): array
    {
        $anggotaList = Anggota::query()->whereHas('user')->orderBy('urutan')->get();

        $menitPerAnggota = Logbook::query()
            ->where('status', Logbook::STATUS_APPROVED)
            ->get()
            ->groupBy('anggota_id')
            ->map(fn (Collection $items) => $items->sum(
                fn (Logbook $l) => DurasiKegiatan::menit($l->jam_mulai, $l->jam_selesai)
            ));

        $baris = $anggotaList->values()->map(function (Anggota $anggota, int $index) use ($menitPerAnggota) {
            $menit = $menitPerAnggota->get($anggota->id, 0);

            return [
                'no' => $index + 1,
                'nim' => $anggota->nim ?? '-',
                'nama' => $anggota->nama,
                'total_jam' => round($menit / 60, 2),
            ];
        });

        return [
            'baris' => $baris,
            'pengaturan' => LayananPengaturan::get(),
        ];
    }

    public static function rentangDefault(): array
    {
        $pengaturan = LayananPengaturan::get();

        return [
            $pengaturan->get('tanggal_mulai_kkn') ?: now()->startOfMonth()->toDateString(),
            $pengaturan->get('tanggal_selesai_kkn') ?: now()->toDateString(),
        ];
    }

    private static function waktuDanDurasi(Logbook $logbook): string
    {
        if (! $logbook->jam_mulai && ! $logbook->jam_selesai) {
            return '-';
        }

        $mulai = $logbook->jam_mulai ? substr($logbook->jam_mulai, 0, 5) : '?';
        $selesai = $logbook->jam_selesai ? substr($logbook->jam_selesai, 0, 5) : '?';
        $durasi = DurasiKegiatan::format($logbook->jam_mulai, $logbook->jam_selesai);

        return "{$mulai} – {$selesai} WIB ({$durasi})";
    }
}
