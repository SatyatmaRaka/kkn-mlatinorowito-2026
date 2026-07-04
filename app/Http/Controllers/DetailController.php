<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\ProgramKerja;
use Illuminate\View\View;

/**
 * Halaman detail publik: profil anggota dan program kerja.
 */
class DetailController extends Controller
{
    public function anggota(Anggota $anggota): View
    {
        return view('detail.anggota', compact('anggota'));
    }

    public function proker(ProgramKerja $programKerja): View
    {
        return view('detail.proker', compact('programKerja'));
    }
}
