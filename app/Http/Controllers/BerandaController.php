<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Galeri;
use App\Models\Kegiatan;
use App\Models\ProgramKerja;
use Illuminate\View\View;

/**
 * Halaman beranda publik — menampilkan profil kelompok KKN.
 * Fitur: hero, daftar anggota, program kerja, kegiatan terbaru, galeri, kontak.
 */
class BerandaController extends Controller
{
    public function index(): View
    {
        $anggota = Anggota::orderBy('urutan')->get();
        $programKerja = ProgramKerja::orderBy('urutan')->get();
        $kegiatan = Kegiatan::orderBy('tanggal', 'desc')->take(3)->get();
        $galeri = Galeri::latest()->take(24)->get();

        return view('beranda', compact('anggota', 'programKerja', 'kegiatan', 'galeri'));
    }
}
