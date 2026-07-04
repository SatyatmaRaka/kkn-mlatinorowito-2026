<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Anggota;
use App\Models\Keuangan;
use App\Models\Logbook;
use App\Models\ProgramKerja;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Dasbor panel — tampilan berbeda per peran & jabatan organisasi KKN.
 */
class DasborController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return match ($this->jenisDasbor($user)) {
            'admin' => $this->dasborAdmin(),
            'koordinator' => $this->dasborKoordinator($user),
            'bendahara' => $this->dasborBendahara($user),
            default => $this->dasborAnggota($user),
        };
    }

    private function jenisDasbor(User $user): string
    {
        if ($user->isAdmin()) {
            return 'admin';
        }

        if ($user->canReviewLogbook()) {
            return 'koordinator';
        }

        if ($user->canManageKeuangan()) {
            return 'bendahara';
        }

        return 'anggota';
    }

    private function dasborAdmin(): View
    {
        return view('dasbor', [
            'totalAnggota' => Anggota::count(),
            'totalProker' => ProgramKerja::count(),
            'prokerTerbaru' => ProgramKerja::orderBy('urutan')->take(5)->get(),
            'logbookMenunggu' => Logbook::where('status', Logbook::STATUS_SUBMITTED)->count(),
            'absensiHariIni' => Absensi::whereDate('tanggal', today())->count(),
        ]);
    }

    private function dasborKoordinator(User $user): View
    {
        $anggotaDenganAkun = Anggota::whereHas('user')->count();
        $catatHariIni = Absensi::whereDate('tanggal', today())->distinct()->count('anggota_id');
        $absensiHariIni = Absensi::whereDate('tanggal', today())->where('status', Absensi::STATUS_HADIR)->count();
        $isWakil = \App\Enums\Jabatan::tryFromValue($user->anggota?->jabatan) === \App\Enums\Jabatan::WakilKoordinator;

        return view('dasbor-koordinator', [
            'user' => $user,
            'judulPeran' => $isWakil ? 'Wakil Koordinator' : 'Koordinator Desa',
            'deskripsiPeran' => $isWakil
                ? 'Mendampingi koordinator — pantau logbook, absensi, dan laporan operasional KKN.'
                : 'Memimpin operasional KKN — pantau logbook, absensi, dan laporan harian.',
            'logbookMenunggu' => Logbook::where('status', Logbook::STATUS_SUBMITTED)->count(),
            'absensiHariIni' => $absensiHariIni,
            'belumAbsen' => max(0, $anggotaDenganAkun - $catatHariIni),
            'logbookReview' => Logbook::with('user.anggota')
                ->where('status', Logbook::STATUS_SUBMITTED)
                ->orderByDesc('updated_at')
                ->take(5)
                ->get(),
        ]);
    }

    private function dasborBendahara(User $user): View
    {
        $totalPemasukan = Keuangan::where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = Keuangan::where('jenis', 'pengeluaran')->sum('nominal');

        return view('dasbor-bendahara', [
            'user' => $user,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $totalPemasukan - $totalPengeluaran,
            'pemasukanBulanIni' => Keuangan::where('jenis', 'pemasukan')
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->sum('nominal'),
            'pengeluaranBulanIni' => Keuangan::where('jenis', 'pengeluaran')
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->sum('nominal'),
            'transaksiTerbaru' => Keuangan::orderByDesc('tanggal')->orderByDesc('created_at')->take(5)->get(),
        ]);
    }

    private function dasborAnggota(User $user): View
    {
        $logbooks = Logbook::where('user_id', $user->id)
            ->orderByDesc('tanggal')
            ->take(5)
            ->get();

        return view('dasbor-anggota', [
            'user' => $user,
            'logbooks' => $logbooks,
            'absensiBulanIni' => Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', now()->month)
                ->whereYear('tanggal', now()->year)
                ->count(),
            'sudahAbsenHariIni' => Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', today())
                ->exists(),
            'divisi' => \App\Enums\Jabatan::infoDasborUntuk($user->anggota?->jabatan),
        ]);
    }
}
