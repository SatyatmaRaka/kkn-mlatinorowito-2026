<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Anggota;
use App\Models\Galeri;
use App\Models\Kegiatan;
use App\Models\Logbook;
use App\Models\ProgramKerja;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Dasbor panel — ringkasan statistik untuk admin/CMS atau anggota biasa.
 */
class DasborController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->canManageCms()) {
            $totalAnggota = Anggota::count();
            $totalProker = ProgramKerja::count();
            $totalKegiatan = Kegiatan::count();
            $totalGaleri = Galeri::count();
            $kegiatanTerbaru = Kegiatan::orderBy('tanggal', 'desc')->take(5)->get();
            $logbookMenunggu = Logbook::where('status', Logbook::STATUS_SUBMITTED)->count();
            $absensiHariIni = Absensi::whereDate('tanggal', today())->count();

            return view('dasbor', compact(
                'totalAnggota',
                'totalProker',
                'totalKegiatan',
                'totalGaleri',
                'kegiatanTerbaru',
                'logbookMenunggu',
                'absensiHariIni',
            ));
        }

        $logbooks = Logbook::where('user_id', $user->id)
            ->orderByDesc('tanggal')
            ->take(5)
            ->get();

        $absensiBulanIni = Absensi::where('user_id', $user->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        $sudahAbsenHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', today())
            ->exists();

        $logbookMenunggu = $user->canReviewLogbook()
            ? Logbook::where('status', Logbook::STATUS_SUBMITTED)->count()
            : 0;

        return view('dasbor-anggota', compact('logbooks', 'absensiBulanIni', 'sudahAbsenHariIni', 'logbookMenunggu'));
    }
}
