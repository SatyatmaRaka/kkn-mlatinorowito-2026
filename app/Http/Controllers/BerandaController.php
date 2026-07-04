<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\ProgramKerja;
use Illuminate\View\View;

/**
 * Halaman beranda publik — menampilkan profil kelompok KKN.
 * Fitur: hero, daftar anggota, program kerja, kontak.
 */
class BerandaController extends Controller
{
    public function index(): View
    {
        $anggota = Anggota::orderBy('urutan')->get();
        $programKerja = ProgramKerja::aktif()->orderBy('urutan')->get();

        return view('beranda', compact('anggota', 'programKerja'));
    }
}
