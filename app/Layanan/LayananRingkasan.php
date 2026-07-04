<?php

namespace App\Layanan;

use App\Models\Absensi;
use App\Models\Anggota;
use App\Models\Keuangan;
use App\Models\Logbook;
use Carbon\Carbon;

/**
 * Agregasi data untuk laporan & endpoint live (polling).
 */
class LayananRingkasan
{
    public static function tanggalMulai(?string $mulai): Carbon
    {
        return $mulai ? Carbon::parse($mulai)->startOfDay() : now()->startOfMonth();
    }

    public static function tanggalSelesai(?string $selesai): Carbon
    {
        return $selesai ? Carbon::parse($selesai)->endOfDay() : now()->endOfDay();
    }

    /** @return array<string, mixed> */
    public static function ringkasanPeriode(?string $mulai = null, ?string $selesai = null, bool $sertakanKeuangan = true): array
    {
        $dari = self::tanggalMulai($mulai);
        $sampai = self::tanggalSelesai($selesai);

        $ringkasan = [
            'periode' => [
                'mulai' => $dari->toDateString(),
                'selesai' => $sampai->toDateString(),
                'label' => $dari->locale('id')->translatedFormat('d M Y').' – '.$sampai->locale('id')->translatedFormat('d M Y'),
            ],
            'absensi' => [
                'total' => Absensi::whereBetween('tanggal', [$dari, $sampai])->count(),
                'hari_ini' => Absensi::whereDate('tanggal', today())->count(),
                'total_anggota_aktif' => Anggota::whereHas('user')->count(),
            ],
            'logbook' => [
                'draft' => Logbook::where('status', Logbook::STATUS_DRAFT)->whereBetween('tanggal', [$dari, $sampai])->count(),
                'submitted' => Logbook::where('status', Logbook::STATUS_SUBMITTED)->whereBetween('tanggal', [$dari, $sampai])->count(),
                'approved' => Logbook::where('status', Logbook::STATUS_APPROVED)->whereBetween('tanggal', [$dari, $sampai])->count(),
                'rejected' => Logbook::where('status', Logbook::STATUS_REJECTED)->whereBetween('tanggal', [$dari, $sampai])->count(),
                'menunggu_review' => Logbook::where('status', Logbook::STATUS_SUBMITTED)->count(),
            ],
        ];

        if ($sertakanKeuangan) {
            $pemasukan = (int) Keuangan::where('jenis', 'pemasukan')
                ->whereBetween('tanggal', [$dari, $sampai])
                ->sum('nominal');

            $pengeluaran = (int) Keuangan::where('jenis', 'pengeluaran')
                ->whereBetween('tanggal', [$dari, $sampai])
                ->sum('nominal');

            $ringkasan['keuangan'] = [
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
                'saldo' => $pemasukan - $pengeluaran,
            ];
        }

        return $ringkasan;
    }

    /** @return array<string, mixed> */
    public static function rekapAbsensiHarian(string $tanggal): array
    {
        $anggotaDenganAkun = Anggota::with('user')
            ->whereHas('user', fn ($q) => $q->whereIn('role', ['anggota', 'koordinator']))
            ->orderBy('urutan')
            ->get();

        $recordsByAnggota = Absensi::with('anggota')
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('anggota_id');

        $hadir = $recordsByAnggota->filter(fn (Absensi $a) => $a->isHadir());
        $izin = $recordsByAnggota->filter(fn (Absensi $a) => $a->isIzin());
        $sakit = $recordsByAnggota->filter(fn (Absensi $a) => $a->isSakit());

        $belum = $anggotaDenganAkun->filter(fn (Anggota $a) => ! $recordsByAnggota->has($a->id));

        return [
            'tanggal' => $tanggal,
            'total' => $anggotaDenganAkun->count(),
            'hadir' => $hadir->count(),
            'izin' => $izin->count(),
            'sakit' => $sakit->count(),
            'belum' => $belum->count(),
            'daftar_hadir' => $hadir->map(fn (Absensi $a) => [
                'nama' => $a->anggota->nama,
                'jam' => $a->check_in_at?->format('H:i') ?? '-',
            ])->values()->all(),
            'daftar_izin' => $izin->map(fn (Absensi $a) => [
                'id' => $a->id,
                'nama' => $a->anggota->nama,
                'keterangan' => $a->keterangan ?? '',
                'metode' => $a->metode,
            ])->values()->all(),
            'daftar_sakit' => $sakit->map(fn (Absensi $a) => [
                'id' => $a->id,
                'nama' => $a->anggota->nama,
                'keterangan' => $a->keterangan ?? '',
                'metode' => $a->metode,
            ])->values()->all(),
            'daftar_belum' => $belum->map(fn (Anggota $a) => [
                'id' => $a->id,
                'nama' => $a->nama,
                'jabatan' => $a->jabatan,
            ])->values()->all(),
        ];
    }
}
