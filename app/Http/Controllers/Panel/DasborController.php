<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Anggota;
use App\Models\Galeri;
use App\Models\Keuangan;
use App\Models\Kegiatan;
use App\Models\Logbook;
use App\Models\ProgramKerja;
use App\Models\Surat;
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
            'sekretaris' => $this->dasborSekretaris($user),
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

        if ($user->canKelolaSurat() && ! $user->isAdmin()) {
            return 'sekretaris';
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
            'totalKegiatan' => Kegiatan::count(),
            'totalGaleri' => Galeri::count(),
            'kegiatanTerbaru' => Kegiatan::orderByDesc('tanggal')->take(5)->get(),
            'logbookMenunggu' => Logbook::where('status', Logbook::STATUS_SUBMITTED)->count(),
            'absensiHariIni' => Absensi::whereDate('tanggal', today())->count(),
        ]);
    }

    private function dasborSekretaris(User $user): View
    {
        return view('dasbor-sekretaris', [
            'user' => $user,
            'suratMasuk' => Surat::where('jenis', 'masuk')->count(),
            'suratKeluar' => Surat::where('jenis', 'keluar')->count(),
            'suratTerbaru' => Surat::orderByDesc('tanggal')->orderByDesc('created_at')->take(5)->get(),
        ]);
    }

    private function dasborKoordinator(User $user): View
    {
        $anggotaDenganAkun = Anggota::whereHas('user')->count();
        $absensiHariIni = Absensi::whereDate('tanggal', today())->count();

        return view('dasbor-koordinator', [
            'user' => $user,
            'logbookMenunggu' => Logbook::where('status', Logbook::STATUS_SUBMITTED)->count(),
            'absensiHariIni' => $absensiHariIni,
            'belumAbsen' => max(0, $anggotaDenganAkun - $absensiHariIni),
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
