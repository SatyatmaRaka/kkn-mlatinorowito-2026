<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Galeri;
use App\Models\Kegiatan;
use App\Models\ProgramKerja;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalAnggota = Anggota::count();
        $totalProker = ProgramKerja::count();
        $totalKegiatan = Kegiatan::count();
        $totalGaleri = Galeri::count();
        $kegiatanTerbaru = Kegiatan::orderBy('tanggal', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'totalAnggota',
            'totalProker',
            'totalKegiatan',
            'totalGaleri',
            'kegiatanTerbaru',
        ));
    }
}
