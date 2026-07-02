<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kegiatan;
use App\Models\ProgramKerja;
use Illuminate\View\View;

/**
 * Halaman detail publik: profil anggota, program kerja, dan kegiatan.
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

    public function kegiatan(Kegiatan $kegiatan): View
    {
        $ogImage = $kegiatan->foto
            ? asset('storage/'.$kegiatan->foto)
            : asset('images/logo.png');

        return view('detail.kegiatan', compact('kegiatan', 'ogImage'));
    }
}
